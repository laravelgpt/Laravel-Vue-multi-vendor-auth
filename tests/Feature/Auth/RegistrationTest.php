<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertInertia(fn ($page) => $page->component('auth/Register'));
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'MySecurePassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '+1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => true,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
