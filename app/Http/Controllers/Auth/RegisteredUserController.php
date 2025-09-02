<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\SecurePassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', new SecurePassword],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:10',
            'notification_preferences' => 'nullable|in:email,sms,push,all,none',
            'bio' => 'nullable|string|max:500',
            'interests' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:255',
            'terms' => 'required|accepted',
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
            'department' => $request->department,
            'employee_id' => $request->employee_id,
            'timezone' => $request->timezone ?? 'UTC',
            'language' => $request->language ?? 'en',
            'notification_preferences' => $request->notification_preferences ?? 'email',
            'bio' => $request->bio,
            'interests' => $request->interests,
            'skills' => $request->skills,
            'registration_ip' => $request->ip(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user role
        if ($user->isAdmin()) {
            return to_route('admin.dashboard');
        }

        return to_route('dashboard');
    }
}
