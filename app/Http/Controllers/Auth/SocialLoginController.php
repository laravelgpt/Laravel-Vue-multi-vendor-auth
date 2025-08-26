<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Social login failed. Please try again.']);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'avatar' => $socialUser->getAvatar(),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(), // Social login users are pre-verified
            ]);
        } else {
            // Update existing user's avatar if they don't have one
            if (!$user->avatar && $socialUser->getAvatar()) {
                $user->update(['avatar' => $socialUser->getAvatar()]);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
