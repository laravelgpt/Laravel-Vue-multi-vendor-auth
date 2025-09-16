<?php

namespace App\Http\Livewire\Auth;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class OtpLogin extends Component
{
    public $email = '';

    public $code = '';

    public $step = 'email'; // 'email' or 'code'

    public $otpSent = false;

    public $resendCooldown = 0;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'code' => 'required|string|size:6',
    ];

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'No account found with this email address.',
        'code.required' => 'OTP code is required.',
        'code.size' => 'OTP code must be 6 digits.',
    ];

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }

    public function sendOtp()
    {
        $this->validate(['email' => 'required|email|exists:users,email']);

        $key = 'otp-send:'.$this->email;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many OTP requests. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        $otpCode = OtpCode::generateCode($this->email);

        // Send OTP via email
        $this->sendOtpEmail($otpCode);

        $this->otpSent = true;
        $this->step = 'code';
        $this->resendCooldown = 60; // 1 minute cooldown

        // Emit event for client-side countdown
        $this->dispatch('otpSent');

        session()->flash('message', 'OTP code sent to your email address.');
    }

    public function verifyOtp()
    {
        $this->validate(['code' => 'required|string|size:6']);

        $otpCode = OtpCode::where('email', $this->email)
            ->where('code', $this->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $otpCode) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired OTP code.',
            ]);
        }

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'code' => 'User not found.',
            ]);
        }

        // Mark OTP as used
        $otpCode->markAsUsed();

        // Login the user
        Auth::login($user, true);

        session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function resendOtp()
    {
        if ($this->resendCooldown > 0) {
            return;
        }

        $key = 'otp-resend:'.$this->email;

        if (RateLimiter::tooManyAttempts($key, 2)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many resend attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        $otpCode = OtpCode::generateCode($this->email);
        $this->sendOtpEmail($otpCode);

        $this->resendCooldown = 60; // 1 minute cooldown
        session()->flash('message', 'New OTP code sent to your email address.');
    }

    public function backToEmail()
    {
        $this->step = 'email';
        $this->code = '';
        $this->otpSent = false;
    }

    public function updatedResendCooldown()
    {
        // Removed automatic countdown to prevent excessive updates
        // Countdown is now handled client-side only
    }

    public function dehydrate()
    {
        // Prevent unnecessary re-renders
        $this->skipRender = false;
    }

    public function render()
    {
        return view('livewire.auth.otp-login')
            ->layout('layouts.auth');
    }

    private function sendOtpEmail(OtpCode $otpCode)
    {
        try {
            Mail::send('emails.otp', [
                'code' => $otpCode->code,
                'expiresAt' => $otpCode->expires_at,
            ], function ($message) {
                $message->to($this->email)
                    ->subject('Your OTP Login Code');
            });
        } catch (\Exception $e) {
            // Log error but don't expose it to user
            \Log::error('Failed to send OTP email: '.$e->getMessage());
        }
    }
}
