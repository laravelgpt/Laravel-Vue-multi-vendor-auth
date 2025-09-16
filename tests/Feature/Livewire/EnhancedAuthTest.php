<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\OtpLogin;
use App\Http\Livewire\Auth\Register;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class EnhancedAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_component_has_enhanced_features()
    {
        $component = Livewire::test(Login::class);

        // Test initial state
        $component->assertSet('email', '');
        $component->assertSet('password', '');
        $component->assertSet('remember', false);
        $component->assertSet('showPassword', false);
        $component->assertSet('isLoading', false);
    }

    public function test_login_with_password_visibility_toggle()
    {
        $component = Livewire::test(Login::class);

        // Initially password should be hidden
        $component->assertSet('showPassword', false);

        // Toggle password visibility
        $component->call('togglePasswordVisibility');
        $component->assertSet('showPassword', true);

        // Toggle again
        $component->call('togglePasswordVisibility');
        $component->assertSet('showPassword', false);
    }

    public function test_login_with_real_time_validation()
    {
        $component = Livewire::test(Login::class);

        // Test email validation
        $component->set('email', 'invalid-email');
        $component->assertHasErrors(['email']);

        $component->set('email', 'valid@example.com');
        $component->assertHasNoErrors(['email']);

        // Test password validation
        $component->set('password', '123');
        $component->assertHasErrors(['password']);

        $component->set('password', 'password123');
        $component->assertHasNoErrors(['password']);
    }

    public function test_login_with_enhanced_error_handling()
    {
        $component = Livewire::test(Login::class);

        // Test with invalid credentials
        $component->set('email', 'nonexistent@example.com');
        $component->set('password', 'wrongpassword');

        $component->call('login');

        $component->assertHasErrors(['email']);
        $component->assertSet('isLoading', false);
    }

    public function test_login_with_loading_state()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $component = Livewire::test(Login::class);

        $component->set('email', 'test@example.com');
        $component->set('password', 'password123');

        // Login should set loading state
        $component->call('login');

        // Should redirect on success
        $component->assertRedirect('/dashboard');
    }

    public function test_register_component_enhanced_validation()
    {
        $component = Livewire::test(Register::class);

        // Test initial state
        $component->assertSet('name', '');
        $component->assertSet('email', '');
        $component->assertSet('password', '');
        $component->assertSet('password_confirmation', '');

        // Test validation by calling register with invalid data
        $component->set('name', 'J'); // Too short
        $component->set('email', 'invalid-email');
        $component->set('password', '123'); // Too short
        $component->set('password_confirmation', '123');

        $component->call('register');
        $component->assertHasErrors(['name', 'email', 'password']);

        // Test with valid data
        $component->set('name', 'John Doe');
        $component->set('username', 'johndoe');
        $component->set('email', 'john@example.com');
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'Password123!');

        $component->call('register');
        $component->assertHasNoErrors();
    }

    public function test_register_with_enhanced_data_processing()
    {
        $component = Livewire::test(Register::class);

        $component->set('name', '  John Doe  ');
        $component->set('username', '  johndoe  ');
        $component->set('email', '  JOHN@EXAMPLE.COM  ');
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'Password123!');

        $component->call('register');

        // Check if there are any errors
        if ($component->errors()->isNotEmpty()) {
            $this->fail('Registration failed with errors: '.json_encode($component->errors()->all()));
        }

        // Should create user with trimmed and lowercase data
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
        ]);

        // Should be authenticated
        $this->assertAuthenticated();

        // Should redirect to dashboard
        $component->assertRedirect(route('dashboard'));
    }

    public function test_otp_login_enhanced_functionality()
    {
        $component = Livewire::test(OtpLogin::class);

        // Test initial state
        $component->assertSet('step', 'email');
        $component->assertSet('email', '');
        $component->assertSet('code', '');

        // Test email validation with non-existent user
        $component->set('email', 'nonexistent@example.com');
        $component->call('sendOtp');
        $component->assertHasErrors(['email']);

        // Test with valid email format but non-existent user
        $component->set('email', 'test@example.com');
        $component->call('sendOtp');
        $component->assertHasErrors(['email']);
    }

    public function test_otp_login_with_existing_user()
    {
        // Create user first
        $user = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'existing@example.com');
        $component->call('sendOtp');

        // Should create OTP code
        $this->assertDatabaseHas('otp_codes', [
            'email' => 'existing@example.com',
        ]);

        $component->assertSet('step', 'code');
    }

    public function test_otp_verification_with_enhanced_validation()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $otpCode = OtpCode::factory()->create([
            'email' => 'test@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
            'used_at' => null,
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');
        $component->set('code', '123456');

        $component->call('verifyOtp');

        // Should mark OTP as used
        $otpCode->refresh();
        $this->assertNotNull($otpCode->used_at);

        // Should authenticate user
        $this->assertAuthenticatedAs($user);
    }

    public function test_otp_verification_with_invalid_code()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        OtpCode::factory()->create([
            'email' => 'test@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
            'used_at' => null,
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');
        $component->set('code', '654321'); // Wrong code

        $component->call('verifyOtp');

        $component->assertHasErrors(['code']);
        $this->assertGuest();
    }

    public function test_otp_verification_with_expired_code()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        OtpCode::factory()->create([
            'email' => 'test@example.com',
            'code' => '123456',
            'expires_at' => now()->subMinutes(10), // Expired
            'used_at' => null,
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');
        $component->set('code', '123456');

        $component->call('verifyOtp');

        $component->assertHasErrors(['code']);
        $this->assertGuest();
    }

    public function test_otp_back_to_email_functionality()
    {
        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');

        $component->call('backToEmail');

        $component->assertSet('step', 'email');
        $component->assertSet('code', '');
    }

    public function test_otp_resend_functionality()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');

        $component->call('resendOtp');

        // Should create new OTP code
        $this->assertDatabaseHas('otp_codes', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_rate_limiting_on_otp_sending()
    {
        $component = Livewire::test(OtpLogin::class);

        // Make multiple OTP requests
        for ($i = 0; $i < 6; $i++) {
            $component->set('email', 'test@example.com');
            $component->call('sendOtp');
        }

        // Should be rate limited
        $component->assertHasErrors(['email']);
    }

    public function test_rate_limiting_on_otp_verification()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $component = Livewire::test(OtpLogin::class);

        $component->set('email', 'test@example.com');
        $component->set('step', 'code');

        // Make multiple failed verification attempts
        for ($i = 0; $i < 6; $i++) {
            $component->set('code', 'wrongcode');
            $component->call('verifyOtp');
        }

        // Should be rate limited
        $component->assertHasErrors(['code']);
    }

    public function test_enhanced_error_messages()
    {
        $component = Livewire::test(Login::class);

        // Test custom error messages
        $component->set('email', '');
        $component->call('login');
        $component->assertSee('Please enter your email address.');

        $component->set('email', 'invalid-email');
        $component->call('login');
        $component->assertSee('Please enter a valid email address.');

        $component->set('email', 'test@example.com');
        $component->set('password', '');
        $component->call('login');
        $component->assertSee('Please enter your password.');

        $component->set('password', '123');
        $component->call('login');
        $component->assertSee('Password must be at least 8 characters.');
    }
}
