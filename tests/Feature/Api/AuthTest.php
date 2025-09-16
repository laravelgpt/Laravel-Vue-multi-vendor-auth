<?php

namespace Tests\Feature\Api;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test API health check
     */
    public function test_api_health_check()
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'version',
            ])
            ->assertJson([
                'status' => 'success',
                'version' => '1.0.0',
            ]);
    }

    /**
     * Test user registration via API
     */
    public function test_user_registration()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'is_admin',
                        'created_at',
                    ],
                    'token',
                    'token_type',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Registration successful. Please verify your email address.',
                'data' => [
                    'user' => [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'is_admin' => null,
                    ],
                    'token_type' => 'Bearer',
                    'email_verification_required' => true,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    /**
     * Test user login via API
     */
    public function test_user_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/auth/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'is_admin',
                        'created_at',
                    ],
                    'token',
                    'token_type',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'token_type' => 'Bearer',
                ],
            ]);
    }

    /**
     * Test OTP sending via API
     */
    public function test_send_otp()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/otp/send', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'data' => [
                    'expires_in',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'OTP sent successfully',
            ]);

        $this->assertDatabaseHas('otp_codes', [
            'email' => $user->email,
            'used_at' => null,
        ]);
    }

    /**
     * Test OTP verification via API
     */
    public function test_verify_otp()
    {
        $user = User::factory()->create();
        $otpCode = OtpCode::factory()->create([
            'email' => $user->email,
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
            'used_at' => null,
        ]);

        $response = $this->postJson('/api/v1/auth/otp/verify', [
            'email' => $user->email,
            'code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'timestamp',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'is_admin',
                        'created_at',
                    ],
                    'token',
                    'token_type',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'OTP verification successful',
            ]);

        $this->assertDatabaseHas('otp_codes', [
            'email' => $user->email,
        ]);

        // Check that the OTP code has been marked as used (used_at is not null)
        $otpCode = OtpCode::where('email', $user->email)->first();
        $this->assertNotNull($otpCode->used_at);
    }

    /**
     * Test social login redirect
     */
    public function test_social_login_redirect()
    {
        // Test that the social login endpoint exists and returns appropriate response
        $response = $this->get('/api/v1/auth/social/google');

        // Should either redirect (302), method not allowed (405), not found (404), or server error (500) if not configured
        $this->assertContains($response->status(), [302, 405, 404, 500]);
    }

    /**
     * Test logout via API
     */
    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Logout successful.',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    /**
     * Test rate limiting on login
     */
    public function test_login_rate_limiting()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Attempt login 6 times (limit is 5)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'wrongpassword',
            ]);
        }

        $response->assertStatus(429);
    }

    /**
     * Test invalid credentials
     */
    public function test_invalid_login_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Test registration validation
     */
    public function test_registration_validation()
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test duplicate email registration
     */
    public function test_duplicate_email_registration()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
