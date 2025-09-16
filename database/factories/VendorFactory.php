<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessTypes = [
            'Electronics Repair',
            'Mobile Repair',
            'Computer Repair',
            'Appliance Repair',
            'Automotive Repair',
            'Home Services',
            'Consulting',
            'Retail',
            'Food & Beverage',
            'Health & Beauty',
        ];

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'country_code' => $this->faker->randomElement(['+1', '+44', '+91', '+49', '+33']),
            'address' => $this->faker->streetAddress(),
            'country' => $this->faker->country(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
            'business_type' => $this->faker->randomElement($businessTypes),
            'description' => $this->faker->paragraph(3),
            'website' => $this->faker->optional(0.7)->url(),
            'logo' => $this->faker->optional(0.5)->imageUrl(200, 200, 'business'),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'is_verified' => $this->faker->boolean(60), // 60% chance of being verified
            'rating' => $this->faker->randomFloat(2, 1.0, 5.0),
            'total_orders' => $this->faker->numberBetween(0, 1000),
            'total_revenue' => $this->faker->randomFloat(2, 0, 50000),
        ];
    }

    /**
     * Indicate that the vendor is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the vendor is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }

    /**
     * Indicate that the vendor is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the vendor has high ratings.
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => $this->faker->randomFloat(2, 4.0, 5.0),
        ]);
    }
}
