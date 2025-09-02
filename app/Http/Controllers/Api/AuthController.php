<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\SecurePassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', new SecurePassword],
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'company' => $request->company,
            'job_title' => $request->job_title,
            'bio' => $request->bio,
            'registration_ip' => $request->ip(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'profile_completion' => $user->getProfileCompletionPercentage(),
                'created_at' => $user->created_at,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string', // Can be email or username
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where($loginField, $request->login)->firstOrFail();

        // Update last login information
        $user->updateLastLogin($request->ip());

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Account is deactivated',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'avatar' => $user->getAvatarUrl(),
                'profile_completion' => $user->getProfileCompletionPercentage(),
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'avatar' => $user->getAvatarUrl(),
                'phone' => $user->phone,
                'bio' => $user->bio,
                'location' => $user->location,
                'website' => $user->website,
                'twitter' => $user->twitter,
                'linkedin' => $user->linkedin,
                'github' => $user->github,
                'date_of_birth' => $user->date_of_birth,
                'gender' => $user->gender,
                'address_line_1' => $user->address_line_1,
                'address_line_2' => $user->address_line_2,
                'city' => $user->city,
                'state' => $user->state,
                'postal_code' => $user->postal_code,
                'country' => $user->country,
                'company' => $user->company,
                'job_title' => $user->job_title,
                'department' => $user->department,
                'employee_id' => $user->employee_id,
                'timezone' => $user->timezone,
                'language' => $user->language,
                'notification_preferences' => $user->notification_preferences,
                'facebook' => $user->facebook,
                'instagram' => $user->instagram,
                'youtube' => $user->youtube,
                'tiktok' => $user->tiktok,
                'interests' => $user->interests,
                'skills' => $user->skills,
                'education' => $user->education,
                'experience' => $user->experience,
                'email_verified' => $user->email_verified,
                'phone_verified' => $user->phone_verified,
                'last_login_at' => $user->last_login_at,
                'registration_ip' => $user->registration_ip,
                'last_login_ip' => $user->last_login_ip,
                'profile_completion' => $user->getProfileCompletionPercentage(),
                'has_complete_profile' => $user->hasCompleteProfile(),
                'full_name' => $user->getFullName(),
                'full_address' => $user->getFullAddress(),
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent to your email',
            ]);
        }

        return response()->json([
            'message' => 'Unable to send password reset link',
        ], 400);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', 'confirmed', new SecurePassword],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset successfully',
            ]);
        }

        return response()->json([
            'message' => 'Unable to reset password',
        ], 400);
    }

    /**
     * Refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed successfully',
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|nullable|string|max:20',
            'bio' => 'sometimes|nullable|string|max:500',
            'location' => 'sometimes|nullable|string|max:255',
            'website' => 'sometimes|nullable|url|max:255',
            'twitter' => 'sometimes|nullable|url|max:255',
            'linkedin' => 'sometimes|nullable|url|max:255',
            'github' => 'sometimes|nullable|url|max:255',
        ]);

        $user->update($request->only([
            'name', 'email', 'phone', 'bio', 'location',
            'website', 'twitter', 'linkedin', 'github',
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'avatar' => $user->getAvatarUrl(),
                'phone' => $user->phone,
                'bio' => $user->bio,
                'location' => $user->location,
                'website' => $user->website,
                'twitter' => $user->twitter,
                'linkedin' => $user->linkedin,
                'github' => $user->github,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'confirmed', new SecurePassword],
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Password changed successfully',
        ]);
    }
}
