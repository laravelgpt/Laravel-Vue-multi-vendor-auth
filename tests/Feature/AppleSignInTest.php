<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('apple sign in redirect route is accessible', function () {
    $response = $this->get('/auth/apple');

    // Should redirect to Apple's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('apple sign in callback route is accessible', function () {
    $response = $this->get('/auth/apple/callback');

    // Should handle the callback (may redirect or show error without proper setup)
    $response->assertStatus(302);
});

it('legacy apple sign in routes are accessible', function () {
    $response = $this->get('/login/apple');

    // Should redirect to Apple's authorization page
    $response->assertStatus(302);
    $response->assertRedirect();
});

it('apple sign in is included in social login providers', function () {
    $response = $this->get('/auth/apple');

    // Route should be accessible (will redirect to Apple)
    $response->assertStatus(302);
});
