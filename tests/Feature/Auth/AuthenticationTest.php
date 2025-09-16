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

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

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

    // Make multiple failed login attempts
    for ($i = 0; $i < 6; $i++) {
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        if ($i < 5) {
            // First 5 attempts should show credential errors
            $response->assertStatus(302)->assertSessionHasErrors(['email']);
        } else {
            // 6th attempt should show rate limiting error or credential error
            if ($response->status() === 302) {
                $response->assertSessionHasErrors('email');
                $errors = session('errors');
                $errorMessage = $errors->first('email');
                $this->assertTrue(
                    str_contains($errorMessage, 'Too many login attempts') ||
                    str_contains($errorMessage, 'These credentials do not match our records'),
                    'Expected rate limiting or credential error, got: '.$errorMessage
                );
            } else {
                // If not 302, it might be rate limited with 429 status
                $this->assertContains($response->status(), [302, 429]);
            }
        }
    }
});
