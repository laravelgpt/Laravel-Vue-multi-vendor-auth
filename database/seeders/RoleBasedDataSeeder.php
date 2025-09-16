<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleBasedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (only if doesn't exist)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Admin Users (only if we don't have enough)
        $existingAdmins = User::where('role', 'admin')->count();
        if ($existingAdmins < 4) {
            $adminUsers = User::factory(4 - $existingAdmins)->admin()->create([
                'email_verified_at' => now(),
            ]);
        }

        // Create Wholeseller Users (only if we don't have enough)
        $existingWholesellers = User::where('role', 'wholeseller')->count();
        if ($existingWholesellers < 10) {
            $wholesellerUsers = User::factory(10 - $existingWholesellers)->wholeseller()->create([
                'email_verified_at' => now(),
            ]);
        }

        // Create Customer Users (only if we don't have enough)
        $existingCustomers = User::where('role', 'customer')->count();
        if ($existingCustomers < 25) {
            $customerUsers = User::factory(25 - $existingCustomers)->customer()->create([
                'email_verified_at' => now(),
            ]);
        }

        // Create Technician Users (only if we don't have enough)
        $existingTechnicians = User::where('role', 'technician')->count();
        if ($existingTechnicians < 8) {
            $technicianUsers = User::factory(8 - $existingTechnicians)->technician()->create([
                'email_verified_at' => now(),
            ]);
        }

        // Create vendors for wholesellers
        $wholesellers = User::where('role', 'wholeseller')->get();
        foreach ($wholesellers as $wholeseller) {
            // Only create vendor if one doesn't exist for this user
            if (! $wholeseller->vendor) {
                Vendor::factory()->create([
                    'user_id' => $wholeseller->id,
                    'email' => $wholeseller->email,
                    'name' => $wholeseller->name.' Business',
                    'business_type' => fake()->randomElement(['Electronics', 'Fashion', 'Home & Garden', 'Sports', 'Books']),
                    'is_active' => true,
                    'is_verified' => fake()->boolean(80), // 80% verified
                ]);
            }
        }

        // Create some additional standalone vendors (only if we don't have enough)
        $existingVendors = Vendor::count();
        if ($existingVendors < 30) {
            Vendor::factory(30 - $existingVendors)->create([
                'is_active' => true,
                'is_verified' => fake()->boolean(70), // 70% verified
            ]);
        }

        // Create some inactive users for testing (only if we don't have enough)
        $inactiveCustomers = User::where('role', 'customer')->where('is_active', false)->count();
        if ($inactiveCustomers < 5) {
            User::factory(5 - $inactiveCustomers)->customer()->create([
                'is_active' => false,
                'email_verified_at' => null,
            ]);
        }

        $inactiveWholesellers = User::where('role', 'wholeseller')->where('is_active', false)->count();
        if ($inactiveWholesellers < 2) {
            User::factory(2 - $inactiveWholesellers)->wholeseller()->create([
                'is_active' => false,
                'email_verified_at' => null,
            ]);
        }

        // Create some unverified vendors (only if we don't have enough)
        $unverifiedVendors = Vendor::where('is_verified', false)->count();
        if ($unverifiedVendors < 5) {
            Vendor::factory(5 - $unverifiedVendors)->create([
                'is_active' => true,
                'is_verified' => false,
            ]);
        }

        $this->command->info('Role-based data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 1 Super Admin');
        $this->command->info('- 3 Admin Users');
        $this->command->info('- 10 Wholeseller Users (with vendors)');
        $this->command->info('- 25 Customer Users');
        $this->command->info('- 8 Technician Users');
        $this->command->info('- 15 Additional Vendors');
        $this->command->info('- 7 Inactive Users');
        $this->command->info('- 5 Unverified Vendors');
    }
}
