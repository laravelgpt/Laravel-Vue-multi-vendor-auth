<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('admin profile page is displayed', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this
        ->actingAs($admin)
        ->get('/admin/profile');

    $response->assertOk();
});

test('non-admin users cannot access admin profile', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this
        ->actingAs($user)
        ->get('/admin/profile');

    $response->assertForbidden();
});

test('admin profile information can be updated', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this
        ->actingAs($admin)
        ->put('/admin/profile', [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'bio' => 'Administrator bio',
            'location' => 'Admin City',
            'website' => 'https://admin.com',
            'twitter' => '@adminuser',
            'linkedin' => 'https://linkedin.com/in/adminuser',
            'github' => 'adminuser',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/admin/profile');

    $admin->refresh();

    expect($admin->name)->toBe('Admin User');
    expect($admin->email)->toBe('admin@example.com');
    expect($admin->phone)->toBe('+1234567890');
    expect($admin->bio)->toBe('Administrator bio');
    expect($admin->location)->toBe('Admin City');
    expect($admin->website)->toBe('https://admin.com');
    expect($admin->twitter)->toBe('@adminuser');
    expect($admin->linkedin)->toBe('https://linkedin.com/in/adminuser');
    expect($admin->github)->toBe('adminuser');
});

test('admin password can be updated', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this
        ->actingAs($admin)
        ->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'new-admin-password',
            'password_confirmation' => 'new-admin-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/admin/profile');

    expect(Hash::check('new-admin-password', $admin->refresh()->password))->toBeTrue();
});

test('admin profile validation works correctly', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this
        ->actingAs($admin)
        ->put('/admin/profile', [
            'name' => '',
            'email' => 'invalid-email',
            'website' => 'not-a-url',
            'linkedin' => 'not-a-url',
        ]);

    $response
        ->assertSessionHasErrors(['name', 'email', 'website', 'linkedin']);
});

test('admin password validation works correctly', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this
        ->actingAs($admin)
        ->put('/admin/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

    $response
        ->assertSessionHasErrors('current_password');
});
