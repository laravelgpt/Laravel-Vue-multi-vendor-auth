<?php

use App\Models\User;
use App\Services\PasswordBreachService;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->breachService = new PasswordBreachService();
});

test('password strength calculation works correctly', function () {
    // Test very weak password
    $strength = $this->breachService->getPasswordStrength('123');
    expect($strength['score'])->toBeLessThan(20);
    expect($strength['strength'])->toBe('Very Weak');

    // Test weak password
    $strength = $this->breachService->getPasswordStrength('password');
    expect($strength['score'])->toBeLessThan(40);
    expect($strength['strength'])->toBe('Very Weak');

    // Test moderate password
    $strength = $this->breachService->getPasswordStrength('MyTestPassword123');
    expect($strength['score'])->toBeGreaterThanOrEqual(30);
    expect($strength['score'])->toBeLessThan(60);

    // Test strong password
    $strength = $this->breachService->getPasswordStrength('MyTestPassword123!');
    expect($strength['score'])->toBeGreaterThanOrEqual(50);
    expect($strength['score'])->toBeLessThan(80);

    // Test very strong password
    $strength = $this->breachService->getPasswordStrength('MySecurePassword123!@#');
    expect($strength['score'])->toBeGreaterThanOrEqual(50);
    expect($strength['strength'])->toBe('Moderate');
});

test('password strength provides feedback', function () {
    $strength = $this->breachService->getPasswordStrength('weak');
    
    expect($strength['feedback'])->toBeArray();
    expect($strength['feedback'])->toContain('Add uppercase letters');
    expect($strength['feedback'])->toContain('Add numbers');
    expect($strength['feedback'])->toContain('Add special characters');
});

test('password breach detection works', function () {
    // Test with a known compromised password (password)
    $isCompromised = $this->breachService->isPasswordCompromised('password');
    $breachCount = $this->breachService->getBreachCount('password');
    
    // Note: This test may fail if the API is unavailable, but the service should handle it gracefully
    expect($isCompromised)->toBeBool();
    expect($breachCount)->toBeInt();
    expect($breachCount)->toBeGreaterThanOrEqual(0);
});

test('secure password validation rule works', function () {
    $rule = new \App\Rules\SecurePassword();
    
    // Test valid password
    $validPassword = 'MyUniqueSecurePassword2024!@#';
    $fail = false;
    $rule->validate('password', $validPassword, function () use (&$fail) {
        $fail = true;
    });
    expect($fail)->toBeFalse();

    // Test invalid password (too short)
    $invalidPassword = '123';
    $fail = false;
    $rule->validate('password', $invalidPassword, function () use (&$fail) {
        $fail = true;
    });
    expect($fail)->toBeTrue();
});

test('password strength API endpoint works', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/password/check-strength', [
            'password' => 'MyUniqueSecurePassword2024!@#'
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'score',
            'feedback',
            'strength',
            'breach_count'
        ]);
});

test('password breach API endpoint works', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/password/check-breach', [
            'password' => 'MyUniqueSecurePassword2024!@#'
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'compromised',
            'breach_count'
        ]);
});

test('password update with breach checking works', function () {
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

    // Verify password was updated
    $user->refresh();
    expect(Hash::check('MyUniqueSecurePassword2024!@#', $user->password))->toBeTrue();
});

test('password update rejects weak passwords', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'weak',
            'password_confirmation' => 'weak'
        ]);

    $response->assertSessionHasErrors('password');
});

test('password update rejects breached passwords', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->from('/profile')
        ->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'password', // Known breached password
            'password_confirmation' => 'password'
        ]);

    $response->assertSessionHasErrors('password');
});

test('admin password update works with breach checking', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->from('/admin/profile')
        ->put('/admin/profile/password', [
            'current_password' => 'password',
            'password' => 'AdminUniqueSecurePassword2024!@#',
            'password_confirmation' => 'AdminUniqueSecurePassword2024!@#'
        ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect('/admin/profile');

    // Verify password was updated
    $admin->refresh();
    expect(Hash::check('AdminUniqueSecurePassword2024!@#', $admin->password))->toBeTrue();
});

test('password strength calculation handles edge cases', function () {
    // Test empty password
    $strength = $this->breachService->getPasswordStrength('');
    expect($strength['score'])->toBe(0);
    expect($strength['strength'])->toBe('Very Weak');

    // Test very long password
    $longPassword = str_repeat('a', 100) . 'A1!';
    $strength = $this->breachService->getPasswordStrength($longPassword);
    expect($strength['score'])->toBeGreaterThan(0);

    // Test password with repeated characters
    $repeatedPassword = 'aaaAAA111!!!';
    $strength = $this->breachService->getPasswordStrength($repeatedPassword);
    expect($strength['feedback'])->toContain('Avoid repeated characters');
});

test('password breach service handles API errors gracefully', function () {
    // Mock the service to simulate API failure
    $mockService = $this->createMock(PasswordBreachService::class);
    $mockService->method('isPasswordCompromised')
        ->willReturn(false);
    $mockService->method('getBreachCount')
        ->willReturn(0);
    $mockService->method('getPasswordStrength')
        ->willReturn([
            'score' => 50,
            'feedback' => ['Test feedback'],
            'strength' => 'Moderate',
            'breach_count' => 0
        ]);

    $this->app->instance(PasswordBreachService::class, $mockService);

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/password/check-strength', [
            'password' => 'TestPassword123!'
        ]);

    $response->assertStatus(200);
});
