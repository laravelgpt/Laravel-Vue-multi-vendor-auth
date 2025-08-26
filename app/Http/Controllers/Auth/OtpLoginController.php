<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OtpLoginController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('auth/OtpLogin', [
            'email' => $request->query('email'),
            'status' => $request->session()->get('status'),
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        $otp = OtpCode::generateCode($email);

        // Send OTP email
        Mail::send('emails.otp', ['code' => $otp->code], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your Login OTP Code');
        });

        return back()->with('status', 'OTP code sent to your email!');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('email', $request->email)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired OTP code.',
            ]);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Log in the user
        Auth::login($user);

        $request->session()->regenerate();

        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
