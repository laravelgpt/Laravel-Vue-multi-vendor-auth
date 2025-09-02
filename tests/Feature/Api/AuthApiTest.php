<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api()
    {
        $userData = [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'is_active',
                    'created_at',
                ],
                'token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'user',
            'is_active' => true,
        ]);
    }

    public function test_user_cannot_register_with_weak_password()
    {
        $userData = [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_with_breached_password()
    {
        $userData = [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'Aa@123123', // Known breached password
            'password_confirmation' => 'Aa@123123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $userData = [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('MySecurePassword123!'),
            'is_active' => true,
        ]);

        $loginData = [
            'login' => 'test@example.com',
            'password' => 'MySecurePassword123!',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'is_active',
                    'avatar',
                    'created_at',
                ],
                'token',
                'token_type',
            ]);

        $this->assertEquals('Bearer', $response->json('token_type'));
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $loginData = [
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    }

    public function test_deactivated_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('MySecurePassword123!'),
            'is_active' => false,
        ]);

        $loginData = [
            'login' => 'test@example.com',
            'password' => 'MySecurePassword123!',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Account is deactivated']);
    }

    public function test_user_can_logout_via_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    }

    public function test_user_can_get_profile_via_api()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
            'is_active' => true,
        ]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'is_active',
                    'avatar',
                    'phone',
                    'bio',
                    'location',
                    'website',
                    'twitter',
                    'linkedin',
                    'github',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertEquals('Test User', $response->json('user.name'));
        $this->assertEquals('test@example.com', $response->json('user.email'));
    }

    public function test_user_can_update_profile_via_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $updateData = [
            'name' => 'Updated Name',
            'bio' => 'This is my bio',
            'location' => 'New York',
            'website' => 'https://example.com',
        ];

        $response = $this->putJson('/api/profile', $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Profile updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'bio' => 'This is my bio',
            'location' => 'New York',
            'website' => 'https://example.com',
        ]);
    }

    public function test_user_can_change_password_via_api()
    {
        $user = User::factory()->create([
            'password' => bcrypt('OldPassword123!'),
        ]);
        Sanctum::actingAs($user);

        $passwordData = [
            'current_password' => 'OldPassword123!',
            'password' => 'MyUniqueTestPassword2024!@#',
            'password_confirmation' => 'MyUniqueTestPassword2024!@#',
        ];

        $response = $this->postJson('/api/change-password', $passwordData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password changed successfully']);
    }

    public function test_user_cannot_change_password_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('OldPassword123!'),
        ]);
        Sanctum::actingAs($user);

        $passwordData = [
            'current_password' => 'WrongPassword123!',
            'password' => 'MyUniqueTestPassword2024!@#',
            'password_confirmation' => 'MyUniqueTestPassword2024!@#',
        ];

        $response = $this->postJson('/api/change-password', $passwordData);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Current password is incorrect']);
    }

    public function test_user_cannot_change_password_with_weak_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('OldPassword123!'),
        ]);
        Sanctum::actingAs($user);

        $passwordData = [
            'current_password' => 'OldPassword123!',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ];

        $response = $this->postJson('/api/change-password', $passwordData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_can_refresh_token_via_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'token_type',
            ]);

        $this->assertEquals('Bearer', $response->json('token_type'));
    }

    public function test_user_can_request_password_reset_via_api()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password reset link sent to your email']);
    }

    public function test_user_cannot_request_password_reset_with_invalid_email()
    {
        $response = $this->postJson('/api/forgot-password', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_protected_routes_require_authentication()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_password_strength_api_works_for_registration()
    {
        $response = $this->postJson('/api/password/check-strength', [
            'password' => 'Aa@123123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'score',
                'strength',
                'breach_count',
                'feedback',
            ]);

        $data = $response->json();
        $this->assertGreaterThan(0, $data['breach_count']);
        $this->assertContains('This password has been found in data breaches', $data['feedback']);
    }

    public function test_password_breach_api_works()
    {
        $response = $this->postJson('/api/password/check-breach', [
            'password' => 'Aa@123123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'compromised',
                'breach_count',
            ]);

        $data = $response->json();
        $this->assertTrue($data['compromised']);
        $this->assertGreaterThan(0, $data['breach_count']);
    }
}
