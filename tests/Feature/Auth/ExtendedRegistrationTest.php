<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtendedRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_username_and_extended_fields(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'company' => 'Tech Corp',
            'job_title' => 'Developer',
            'timezone' => 'America/New_York',
            'language' => 'en',
            'notification_preferences' => 'email',
            'bio' => 'Software developer with 5 years experience',
            'interests' => 'Programming, Reading, Travel',
            'skills' => 'PHP, JavaScript, Vue.js',
            'terms' => true,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'gender' => 'male',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'company' => 'Tech Corp',
            'job_title' => 'Developer',
            'timezone' => 'America/New_York',
            'language' => 'en',
            'notification_preferences' => 'email',
            'bio' => 'Software developer with 5 years experience',
            'interests' => 'Programming, Reading, Travel',
            'skills' => 'PHP, JavaScript, Vue.js',
        ]);
    }

    public function test_user_cannot_register_with_duplicate_username(): void
    {
        User::factory()->create(['username' => 'johndoe']);

        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'username' => 'johndoe', // Duplicate username
            'email' => 'jane@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_user_cannot_register_with_invalid_username_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'john-doe', // Invalid format (contains hyphen)
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $response = $this->post('/login', [
            'login' => 'johndoe', // Using username instead of email
            'password' => 'MySecurePassword123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $response = $this->post('/login', [
            'login' => 'john@example.com', // Using email
            'password' => 'MySecurePassword123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $response = $this->post('/login', [
            'login' => 'johndoe',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_registration_requires_username(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            // Missing username
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_user_model_has_new_helper_methods(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => '123 Main St',
            'address_line_2' => null,
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
        ]);

        $this->assertEquals('John Doe', $user->getFullName());
        $this->assertEquals('123 Main St, New York, NY, 10001, USA', $user->getFullAddress());
        $this->assertTrue($user->hasCompleteProfile());
        $this->assertGreaterThan(0, $user->getProfileCompletionPercentage());
    }

    public function test_api_registration_with_extended_fields(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'role',
                    'is_active',
                    'profile_completion',
                    'created_at',
                ],
                'token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    public function test_api_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'johndoe',
            'password' => 'MySecurePassword123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'role',
                    'is_active',
                    'avatar',
                    'profile_completion',
                    'last_login_at',
                    'created_at',
                ],
                'token',
                'token_type',
            ]);
    }

    public function test_api_login_with_email(): void
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('MySecurePassword123!'),
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'john@example.com',
            'password' => 'MySecurePassword123!',
        ]);

        $response->assertStatus(200);
    }
}
