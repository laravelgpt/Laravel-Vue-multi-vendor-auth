<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VueRegistrationFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_loads_with_multi_step_form(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('auth/Register')
            ->has('errors')
        );
    }

    public function test_step_1_validation_requires_all_account_fields(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'username' => 'The username field is required.',
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    public function test_step_1_validation_requires_matching_passwords(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'The password field confirmation does not match.',
        ]);
    }

    public function test_step_1_validation_requires_strong_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'The password must be at least 8 characters.',
        ]);
    }

    public function test_step_2_validation_requires_personal_details(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => '',
            'last_name' => '',
            'phone' => '',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'phone' => 'The phone field is required.',
        ]);
    }

    public function test_step_3_validation_requires_address_fields(): void
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
            'address_line_1' => '',
            'city' => '',
            'country' => '',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors([
            'address_line_1' => 'The address line 1 field is required.',
            'city' => 'The city field is required.',
            'country' => 'The country field is required.',
        ]);
    }

    public function test_complete_registration_with_all_steps_succeeds(): void
    {
        $userData = [
            // Step 1: Account Information
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',

            // Step 2: Personal Details
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',

            // Step 3: Address Information
            'address_line_1' => '123 Main St',
            'address_line_2' => 'Apt 4B',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',

            // Step 4: Professional Information
            'company' => 'Tech Corp',
            'job_title' => 'Software Engineer',
            'department' => 'Engineering',
            'employee_id' => 'EMP001',

            // Step 5: Preferences
            'timezone' => 'America/New_York',
            'language' => 'en',
            'notification_preferences' => 'email',
            'bio' => 'Passionate software engineer',
            'interests' => 'Coding, Reading, Travel',
            'skills' => 'JavaScript, Python, Vue.js',

            // Terms and conditions
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'city' => 'New York',
            'country' => 'USA',
            'company' => 'Tech Corp',
            'job_title' => 'Software Engineer',
        ]);
    }

    public function test_registration_with_minimal_required_fields_succeeds(): void
    {
        $userData = [
            // Step 1: Account Information (required)
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',

            // Step 2: Personal Details (required)
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+1987654321',

            // Step 3: Address Information (required)
            'address_line_1' => '456 Oak Ave',
            'city' => 'Los Angeles',
            'country' => 'USA',

            // Terms and conditions (required)
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '+1987654321',
            'city' => 'Los Angeles',
            'country' => 'USA',
        ]);
    }

    public function test_registration_requires_terms_acceptance(): void
    {
        $userData = [
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
            'terms' => false, // Not accepted
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors([
            'terms' => 'The terms field must be accepted.',
        ]);
    }

    public function test_registration_with_duplicate_username_fails(): void
    {
        // Create existing user
        User::factory()->create([
            'username' => 'existinguser',
            'email' => 'existing@example.com',
        ]);

        $userData = [
            'name' => 'John Doe',
            'username' => 'existinguser', // Duplicate username
            'email' => 'john@example.com',
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors([
            'username' => 'The username has already been taken.',
        ]);
    }

    public function test_registration_with_duplicate_email_fails(): void
    {
        // Create existing user
        User::factory()->create([
            'username' => 'existinguser',
            'email' => 'existing@example.com',
        ]);

        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'existing@example.com', // Duplicate email
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors([
            'email' => 'The email has already been taken.',
        ]);
    }

    public function test_registration_with_invalid_email_format_fails(): void
    {
        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'invalid-email', // Invalid email format
            'password' => 'MySecurePassword123!',
            'password_confirmation' => 'MySecurePassword123!',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertSessionHasErrors([
            'email' => 'The email field must be a valid email address.',
        ]);
    }

    public function test_registration_creates_user_with_default_role(): void
    {
        $userData = [
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
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
        $this->assertTrue($user->is_active);
    }

    public function test_registration_sets_default_values_for_optional_fields(): void
    {
        $userData = [
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
            'terms' => true,
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('UTC', $user->timezone);
        $this->assertEquals('en', $user->language);
        $this->assertEquals('email', $user->notification_preferences);
    }
}
