<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * API Response Helper
     */
    private function apiResponse(string $status, string $message, $data = null, int $code = 200): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * User Login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            $user = Auth::user();

            // Check if user account is active
            if (! $user->is_active ?? true) {
                Auth::logout();

                return $this->apiResponse('error', 'Your account has been deactivated. Please contact support.', null, 403);
            }

            // Create token with expiration
            $token = $user->createToken('auth-token', ['*'], now()->addDays(30))->plainTextToken;

            // Update last login timestamp
            $user->update(['last_login_at' => now()]);

            // Log successful login
            \Log::info('User login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $this->apiResponse('success', 'Login successful', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'is_admin' => $user->is_admin,
                    'last_login_at' => $user->last_login_at,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 2592000, // 30 days in seconds
            ]);

        } catch (ValidationException $e) {
            return $this->apiResponse('error', 'Invalid credentials', [
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Login error: '.$e->getMessage());

            return $this->apiResponse('error', 'Login failed. Please try again.', null, 500);
        }
    }

    /**
     * User Registration
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create user with additional fields
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username ?? strtolower(str_replace(' ', '', $request->name)),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => null, // Require email verification
                'last_login_at' => null,
            ]);

            // Create token with expiration
            $token = $user->createToken('auth-token', ['*'], now()->addDays(30))->plainTextToken;

            // Send email verification (optional)
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                \Log::warning('Failed to send verification email: '.$e->getMessage());
            }

            // Log successful registration
            \Log::info('User registration successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return $this->apiResponse('success', 'Registration successful. Please verify your email address.', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'is_admin' => $user->is_admin,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 2592000, // 30 days in seconds
                'email_verification_required' => true,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Registration error: '.$e->getMessage());

            return $this->apiResponse('error', 'Registration failed. Please try again.', null, 500);
        }
    }

    /**
     * Send OTP for Login
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        $key = 'otp:'.$email;

        // Rate limiting
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return $this->apiResponse('error', "Too many OTP attempts. Please try again in {$seconds} seconds.", null, 429);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        // Generate OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        // Store OTP
        OtpCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => $expiresAt,
                'used' => false,
            ]
        );

        // Send OTP via email
        try {
            Mail::send('emails.otp', [
                'code' => $code,
                'expiresAt' => $expiresAt,
            ], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your OTP Login Code');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: '.$e->getMessage());

            return $this->apiResponse('error', 'Failed to send OTP. Please try again.', null, 500);
        }

        return $this->apiResponse('success', 'OTP sent successfully', [
            'expires_in' => 600, // 10 minutes in seconds
        ]);
    }

    /**
     * Verify OTP and Login
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        $otpCode = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpCode) {
            return $this->apiResponse('error', 'Invalid or expired OTP code.', null, 400);
        }

        // Mark OTP as used
        $otpCode->markAsUsed();

        // Login user
        $user = User::where('email', $request->email)->first();
        Auth::login($user);
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->apiResponse('success', 'OTP verification successful', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Social Login Redirect
     */
    public function socialRedirect(string $provider): JsonResponse
    {
        if (! in_array($provider, ['google', 'github', 'facebook', 'apple'])) {
            return $this->apiResponse('error', 'Invalid social provider.', null, 400);
        }

        $redirectUrl = Socialite::driver($provider)->redirect()->getTargetUrl();

        return $this->apiResponse('success', 'Redirect URL generated', [
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * Social Login Callback
     */
    public function socialCallback(string $provider): JsonResponse
    {
        if (! in_array($provider, ['google', 'github', 'facebook', 'apple'])) {
            return $this->apiResponse('error', 'Invalid social provider.', null, 400);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return $this->apiResponse('error', 'Social login failed.', null, 400);
        }

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user);
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->apiResponse('success', 'Social login successful', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Forgot Password
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $request->ensureIsNotRateLimited();

            $email = $request->email;
            $user = User::where('email', $email)->first();

            // Check if user account is active
            if (! $user->is_active ?? true) {
                return $this->apiResponse('error', 'Your account has been deactivated. Please contact support.', null, 403);
            }

            // Generate password reset token
            $token = Str::random(64);
            $expiresAt = now()->addMinutes(60); // Token expires in 1 hour

            // Store password reset token in database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'email' => $email,
                    'token' => Hash::make($token),
                    'created_at' => now(),
                    'expires_at' => $expiresAt,
                ]
            );

            // Send password reset email
            try {
                Mail::send('emails.password-reset', [
                    'user' => $user,
                    'token' => $token,
                    'expiresAt' => $expiresAt,
                    'resetUrl' => config('app.frontend_url').'/reset-password?token='.$token.'&email='.urlencode($email),
                ], function ($message) use ($email, $user) {
                    $message->to($email, $user->name)
                        ->subject('Password Reset Request');
                });

                // Log password reset request
                \Log::info('Password reset requested', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return $this->apiResponse('success', 'Password reset link sent to your email address.', [
                    'expires_in' => 3600, // 1 hour in seconds
                ]);

            } catch (\Exception $e) {
                \Log::error('Failed to send password reset email: '.$e->getMessage());

                return $this->apiResponse('error', 'Failed to send password reset email. Please try again.', null, 500);
            }

        } catch (ValidationException $e) {
            return $this->apiResponse('error', 'Invalid request', [
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Forgot password error: '.$e->getMessage());

            return $this->apiResponse('error', 'Password reset request failed. Please try again.', null, 500);
        }
    }

    /**
     * Reset Password
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $email = $request->email;
            $token = $request->token;
            $password = $request->password;

            // Find the password reset token
            $resetToken = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('expires_at', '>', now())
                ->first();

            if (! $resetToken) {
                return $this->apiResponse('error', 'Invalid or expired reset token.', null, 400);
            }

            // Verify the token
            if (! Hash::check($token, $resetToken->token)) {
                return $this->apiResponse('error', 'Invalid reset token.', null, 400);
            }

            // Find the user
            $user = User::where('email', $email)->first();
            if (! $user) {
                return $this->apiResponse('error', 'User not found.', null, 404);
            }

            // Check if user account is active
            if (! $user->is_active ?? true) {
                return $this->apiResponse('error', 'Your account has been deactivated. Please contact support.', null, 403);
            }

            DB::beginTransaction();

            // Update user password
            $user->update([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ]);

            // Delete the used reset token
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            // Revoke all existing tokens for security
            $user->tokens()->delete();

            // Log password reset
            \Log::info('Password reset successful', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return $this->apiResponse('success', 'Password reset successful. Please login with your new password.', [
                'login_required' => true,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Password reset error: '.$e->getMessage());

            return $this->apiResponse('error', 'Password reset failed. Please try again.', null, 500);
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->apiResponse('success', 'Logout successful.');
    }
}
