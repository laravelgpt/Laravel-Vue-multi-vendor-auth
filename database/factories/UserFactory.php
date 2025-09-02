<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $username = strtolower($firstName.$lastName.fake()->numberBetween(1, 999));

        return [
            'name' => fake()->name(),
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other', 'prefer_not_to_say']),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'company' => fake()->optional()->company(),
            'job_title' => fake()->optional()->jobTitle(),
            'department' => fake()->optional()->word(),
            'employee_id' => fake()->optional()->numerify('EMP####'),
            'timezone' => fake()->timezone(),
            'language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it']),
            'notification_preferences' => fake()->randomElement(['email', 'sms', 'push', 'all', 'none']),
            'bio' => fake()->optional()->paragraph(),
            'interests' => fake()->optional()->words(3, true),
            'skills' => fake()->optional()->words(3, true),
            'role' => 'user',
            'is_active' => true,
            'email_verified' => true,
            'phone_verified' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
