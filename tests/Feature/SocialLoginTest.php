<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('google sign in redirect route is accessible', function () {
    $response = $this->get('/auth/google');

    // Should redirect to Google's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('facebook sign in redirect route is accessible', function () {
    $response = $this->get('/auth/facebook');

    // Should redirect to Facebook's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('github sign in redirect route is accessible', function () {
    $response = $this->get('/auth/github');

    // Should redirect to GitHub's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('apple sign in redirect route is accessible', function () {
    $response = $this->get('/auth/apple');

    // Should redirect to Apple's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('social login callback routes are accessible', function () {
    $providers = ['google', 'facebook', 'github', 'apple'];

    foreach ($providers as $provider) {
        $response = $this->get("/auth/{$provider}/callback");

        // Should handle the callback (may redirect or show error without proper setup)
        $response->assertStatus(302);
    }
});

it('named routes work correctly', function () {
    $providers = [
        'google' => 'auth.google',
        'facebook' => 'auth.facebook',
        'github' => 'auth.github',
        'apple' => 'auth.apple',
    ];

    foreach ($providers as $provider => $routeName) {
        $response = $this->get(route($routeName));

        // Should redirect to provider's authorization page
        $response->assertStatus(302);
        $response->assertRedirect();
    }
});

it('legacy routes work correctly', function () {
    $providers = ['google', 'facebook', 'github', 'apple'];

    foreach ($providers as $provider) {
        $response = $this->get("/login/{$provider}");

        // Should redirect to provider's authorization page
        $response->assertStatus(302);
        $response->assertRedirect();
    }
});
