<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPasswordBreachTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_displays_with_password_strength_meter()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('auth/Register'));
    }

    public function test_registration_rejects_breached_password()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Aa@123123', // Known breached password
            'password_confirmation' => 'Aa@123123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_accepts_strong_password()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'MyUniqueSecurePassword2024!@#',
            'password_confirmation' => 'MyUniqueSecurePassword2024!@#',
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '1234567890',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'terms' => true,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    public function test_registration_rejects_weak_password()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_rejects_password_without_uppercase()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'mypassword123!',
            'password_confirmation' => 'mypassword123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_rejects_password_without_lowercase()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'MYPASSWORD123!',
            'password_confirmation' => 'MYPASSWORD123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_rejects_password_without_numbers()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'MyPassword!',
            'password_confirmation' => 'MyPassword!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_rejects_password_without_symbols()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'MyPassword123',
            'password_confirmation' => 'MyPassword123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_requires_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_registration_requires_all_fields()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_registration_requires_valid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_requires_unique_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_strength_api_endpoint_works_for_registration()
    {
        $response = $this->postJson('/api/password/check-strength', [
            'password' => 'Aa@123123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'score',
            'strength',
            'breach_count',
            'feedback',
        ]);

        $data = $response->json();
        $this->assertGreaterThan(0, $data['breach_count']);
        $this->assertContains('This password has been found in data breaches', $data['feedback']);
    }
}
