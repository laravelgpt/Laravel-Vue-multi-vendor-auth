<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/profile');

    $response->assertStatus(200);
});

test('admin profile page is displayed', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->get('/admin/profile');

    $response->assertStatus(200);
});

test('user can update profile information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1234567890',
            'bio' => 'Updated bio',
            'location' => 'New York, USA',
            'website' => 'https://example.com',
            'twitter' => '@updateduser',
            'linkedin' => 'https://linkedin.com/in/updateduser',
            'github' => 'updateduser'
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('updated@example.com');
    expect($user->phone)->toBe('+1234567890');
    expect($user->bio)->toBe('Updated bio');
    expect($user->location)->toBe('New York, USA');
    expect($user->website)->toBe('https://example.com');
    expect($user->twitter)->toBe('@updateduser');
    expect($user->linkedin)->toBe('https://linkedin.com/in/updateduser');
    expect($user->github)->toBe('updateduser');
});

test('admin can update profile information', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->from('/admin/profile')
        ->put('/admin/profile', [
            'name' => 'Updated Admin',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'bio' => 'Admin bio',
            'location' => 'Admin City',
            'website' => 'https://admin.com',
            'twitter' => '@adminuser',
            'linkedin' => 'https://linkedin.com/in/adminuser',
            'github' => 'adminuser'
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/admin/profile');

    $admin->refresh();
    expect($admin->name)->toBe('Updated Admin');
    expect($admin->email)->toBe('admin@example.com');
});

test('profile update validates email uniqueness', function () {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);

    $response = $this->actingAs($user1)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'user2@example.com', // Email already taken
            'phone' => '+1234567890'
        ]);

    $response->assertSessionHasErrors('email');
});

test('profile update validates email format', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'invalid-email',
            'phone' => '+1234567890'
        ]);

    $response->assertSessionHasErrors('email');
});

test('profile update validates website URL format', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'user@example.com',
            'website' => 'invalid-url'
        ]);

    $response->assertSessionHasErrors('website');
});

test('profile update validates LinkedIn URL format', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'user@example.com',
            'linkedin' => 'invalid-linkedin-url'
        ]);

    $response->assertSessionHasErrors('linkedin');
});

test('profile update allows optional fields to be empty', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'user@example.com',
            'phone' => '',
            'bio' => '',
            'location' => '',
            'website' => '',
            'twitter' => '',
            'linkedin' => '',
            'github' => ''
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->email)->toBe('user@example.com');
    expect($user->phone)->toBeNull();
    expect($user->bio)->toBeNull();
});

test('user can update password with secure password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'MyUniqueSecurePassword2024!@#',
            'password_confirmation' => 'MyUniqueSecurePassword2024!@#'
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/settings/password');

    $user->refresh();
    expect(Hash::check('MyUniqueSecurePassword2024!@#', $user->password))->toBeTrue();
});

test('admin can update password with secure password', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->from('/admin/profile')
        ->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'AdminSecurePassword123!',
            'password_confirmation' => 'AdminSecurePassword123!'
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/admin/profile');

    $admin->refresh();
    expect(Hash::check('AdminSecurePassword123!', $admin->password))->toBeTrue();
});

test('password update requires current password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!'
        ]);

    $response->assertSessionHasErrors('current_password');
});

test('password update requires password confirmation', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'DifferentPassword123!'
        ]);

    $response->assertSessionHasErrors('password');
});

test('user can delete account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'password'
        ]);

    $response->assertRedirect('/');

    // Verify user is deleted
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('account deletion requires correct password', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password'
        ]);

    $response->assertSessionHasErrors('password');

    // Verify user is not deleted
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

test('profile pages are accessible only to authenticated users', function () {
    // Test user profile
    $response = $this->get('/profile');
    $response->assertRedirect('/login');

    // Test admin profile
    $response = $this->get('/admin/profile');
    $response->assertRedirect('/login');
});

test('admin profile is accessible only to admin users', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)
        ->get('/admin/profile');

    $response->assertStatus(403);
});

test('profile update maintains user role', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

    $response->assertSessionHasNoErrors();

    $user->refresh();
    expect($user->role)->toBe('user');
});

test('admin profile update maintains admin role', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->from('/admin/profile')
        ->put('/admin/profile', [
            'name' => 'Updated Admin',
            'email' => 'admin@example.com'
        ]);

    $response->assertSessionHasNoErrors();

    $admin->refresh();
    expect($admin->role)->toBe('admin');
});
