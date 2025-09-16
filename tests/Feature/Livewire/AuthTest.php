<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\OtpLogin;
use App\Http\Livewire\Auth\Register;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test login component renders
     */
    public function test_login_component_renders()
    {
        $component = Livewire::test(Login::class);

        $component->assertStatus(200);
    }

    /**
     * Test login with valid credentials
     */
    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->call('login');

        $component->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Test login with invalid credentials
     */
    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $component = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrongpassword')
            ->call('login');

        $component->assertHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test login validation
     */
    public function test_login_validation()
    {
        $component = Livewire::test(Login::class)
            ->set('email', '')
            ->set('password', '')
            ->call('login');

        $component->assertHasErrors(['email', 'password']);
    }

    /**
     * Test remember me functionality
     */
    public function test_remember_me_functionality()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $component = Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->set('remember', true)
            ->call('login');

        $component->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /**
     * Test register component renders
     */
    public function test_register_component_renders()
    {
        $component = Livewire::test(Register::class);

        $component->assertStatus(200);
    }

    /**
     * Test user registration
     */
    public function test_user_registration()
    {
        $userData = [
            'name' => $this->faker->name,
            'username' => 'user'.$this->faker->unique()->numberBetween(1000, 9999),
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $component = Livewire::test(Register::class)
            ->set('name', $userData['name'])
            ->set('username', $userData['username'])
            ->set('email', $userData['email'])
            ->set('password', $userData['password'])
            ->set('password_confirmation', $userData['password_confirmation'])
            ->call('register');

        // Debug: Check for errors
        if ($component->errors()->isNotEmpty()) {
            $this->fail('Registration failed with errors: '.json_encode($component->errors()->all()));
        }

        $component->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'username' => $userData['username'],
            'email' => $userData['email'],
        ]);
    }

    /**
     * Test registration validation
     */
    public function test_registration_validation()
    {
        $component = Livewire::test(Register::class)
            ->set('name', '')
            ->set('email', '')
            ->set('password', '')
            ->set('password_confirmation', '')
            ->call('register');

        $component->assertHasErrors(['name', 'email', 'password']);
    }

    /**
     * Test password confirmation mismatch
     */
    public function test_password_confirmation_mismatch()
    {
        $component = Livewire::test(Register::class)
            ->set('name', $this->faker->name)
            ->set('email', $this->faker->unique()->safeEmail)
            ->set('password', 'password123')
            ->set('password_confirmation', 'differentpassword')
            ->call('register');

        $component->assertHasErrors(['password']);
    }

    /**
     * Test duplicate email registration
     */
    public function test_duplicate_email_registration()
    {
        $user = User::factory()->create();

        $component = Livewire::test(Register::class)
            ->set('name', $this->faker->name)
            ->set('email', $user->email)
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register');

        $component->assertHasErrors(['email']);
    }

    /**
     * Test OTP login component renders
     */
    public function test_otp_login_component_renders()
    {
        $component = Livewire::test(OtpLogin::class);

        $component->assertStatus(200);
    }

    /**
     * Test OTP sending
     */
    public function test_otp_sending()
    {
        $user = User::factory()->create();

        $component = Livewire::test(OtpLogin::class)
            ->set('email', $user->email)
            ->call('sendOtp');

        $component->assertSet('step', 'code');
        $component->assertSee('6-digit code');

        $this->assertDatabaseHas('otp_codes', [
            'email' => $user->email,
            'used_at' => null,
        ]);
    }

    /**
     * Test OTP verification
     */
    public function test_otp_verification()
    {
        $user = User::factory()->create();
        $otpCode = OtpCode::factory()->create([
            'email' => $user->email,
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
            'used_at' => null,
        ]);

        $component = Livewire::test(OtpLogin::class)
            ->set('email', $user->email)
            ->set('step', 'code')
            ->set('code', '123456')
            ->call('verifyOtp');

        $component->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('otp_codes', [
            'email' => $user->email,
        ]);

        // Check that the OTP was marked as used
        $otpCode = OtpCode::where('email', $user->email)->first();
        $this->assertNotNull($otpCode->used_at);
    }

    /**
     * Test invalid OTP verification
     */
    public function test_invalid_otp_verification()
    {
        $user = User::factory()->create();

        $component = Livewire::test(OtpLogin::class)
            ->set('email', $user->email)
            ->set('step', 'code')
            ->set('code', '000000')
            ->call('verifyOtp');

        $component->assertHasErrors(['code']);
        $this->assertGuest();
    }

    /**
     * Test back to email functionality
     */
    public function test_back_to_email_functionality()
    {
        $component = Livewire::test(OtpLogin::class)
            ->set('step', 'code')
            ->call('backToEmail');

        $component->assertSet('step', 'email');
    }

    /**
     * Test OTP email validation
     */
    public function test_otp_email_validation()
    {
        $component = Livewire::test(OtpLogin::class)
            ->set('email', '')
            ->call('sendOtp');

        $component->assertHasErrors(['email']);
    }

    /**
     * Test OTP code validation
     */
    public function test_otp_code_validation()
    {
        $component = Livewire::test(OtpLogin::class)
            ->set('step', 'code')
            ->set('code', '')
            ->call('verifyOtp');

        $component->assertHasErrors(['code']);
    }

    /**
     * Test expired OTP verification
     */
    public function test_expired_otp_verification()
    {
        $user = User::factory()->create();
        $otpCode = OtpCode::factory()->create([
            'email' => $user->email,
            'code' => '123456',
            'expires_at' => now()->subMinutes(10),
            'used_at' => null,
        ]);

        $component = Livewire::test(OtpLogin::class)
            ->set('email', $user->email)
            ->set('step', 'code')
            ->set('code', '123456')
            ->call('verifyOtp');

        $component->assertHasErrors(['code']);
        $this->assertGuest();
    }

    /**
     * Test rate limiting on OTP sending
     */
    public function test_otp_rate_limiting()
    {
        $user = User::factory()->create();

        // Send OTP multiple times to trigger rate limiting
        for ($i = 0; $i < 6; $i++) {
            $component = Livewire::test(OtpLogin::class)
                ->set('email', $user->email)
                ->call('sendOtp');
        }

        $component->assertHasErrors(['email']);
    }
}
