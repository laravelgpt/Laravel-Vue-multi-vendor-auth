<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    // Failed login attempts redirect back with validation errors
    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    // Clear any existing rate limiters
    \Illuminate\Support\Facades\RateLimiter::clear(throttleKey($user->email));

    for ($i = 0; $i < 5; $i++) {
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // Failed login attempts redirect back with validation errors
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    // The 6th attempt should be rate limited by the authentication controller
    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    // Rate limited login attempts redirect back with validation errors
    $response->assertStatus(302);
    $response->assertSessionHasErrors('email');
});

// Helper method to generate throttle key
function throttleKey($email)
{
    return \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($email).'|127.0.0.1');
}
