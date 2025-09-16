<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public $showPassword = false;

    public $isLoading = false;

    protected $rules = [
        'email' => 'required|email|max:255',
        'password' => 'required|min:8',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Email address is too long.',
        'password.required' => 'Please enter your password.',
        'password.min' => 'Password must be at least 8 characters.',
    ];

    public function login()
    {
        try {
            $this->isLoading = true;
            $this->validate();

            $this->ensureIsNotRateLimited();

            $credentials = [
                'email' => strtolower(trim($this->email)),
                'password' => $this->password,
            ];

            if (Auth::attempt($credentials, $this->remember)) {
                session()->regenerate();

                // Clear rate limiting on successful login
                RateLimiter::clear($this->throttleKey());

                // Log successful login
                $user = Auth::user();
                Log::info('User logged in successfully via Livewire', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                // Show success message
                session()->flash('success', 'Welcome back, '.$user->name.'!');

                // Redirect based on user role
                if ($user->isAdmin()) {
                    return redirect()->intended(route('admin.dashboard'));
                }

                return redirect()->intended(route('dashboard'));
            }

            // Log failed login attempt
            Log::warning('Failed login attempt via Livewire', [
                'email' => $this->email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);

        } catch (ValidationException $e) {
            $this->isLoading = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isLoading = false;

            // Log unexpected errors
            Log::error('Unexpected error during Livewire login', [
                'email' => $this->email,
                'ip_address' => request()->ip(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->addError('email', 'An unexpected error occurred. Please try again.');
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 10)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth');
    }
}
