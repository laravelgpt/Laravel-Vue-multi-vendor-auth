<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): View
    {
        return view('livewire.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Rate limiting for registration
            $this->ensureIsNotRateLimited($request);

            $request->validate([
                'name' => 'required|string|max:255|min:2',
                'username' => 'required|string|max:255|min:3|unique:users|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'name.required' => 'Please enter your full name.',
                'name.min' => 'Name must be at least 2 characters long.',
                'username.required' => 'Please enter a username.',
                'username.min' => 'Username must be at least 3 characters long.',
                'username.unique' => 'This username is already taken.',
                'username.regex' => 'Username can only contain letters, numbers, and underscores.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already registered.',
                'password.required' => 'Please enter a password.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]);

            $user = User::create([
                'name' => trim($request->name),
                'username' => trim($request->username),
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'is_active' => true,
            ]);

            // Log successful registration
            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            event(new Registered($user));

            Auth::login($user);

            // Clear rate limiting on successful registration
            RateLimiter::clear($this->throttleKey($request));

            // Redirect based on user role with success message
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome to the platform, '.$user->name.'! Your account has been created successfully.');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Welcome to the platform, '.$user->name.'! Your account has been created successfully.');

        } catch (ValidationException $e) {
            // Log failed registration attempt
            Log::warning('Failed registration attempt', [
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'errors' => $e->errors(),
            ]);

            throw $e;
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error during registration', [
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'ip_address' => $request->ip(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['email' => 'An unexpected error occurred. Please try again.']);
        }
    }

    /**
     * Ensure the registration request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}
