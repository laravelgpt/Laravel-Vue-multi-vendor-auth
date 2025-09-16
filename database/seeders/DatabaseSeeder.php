<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users for each role
        User::factory()->admin()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'username' => 'testadmin',
        ]);

        User::factory()->wholeseller()->create([
            'name' => 'Test Wholeseller',
            'email' => 'wholeseller@test.com',
            'username' => 'testwholeseller',
        ]);

        User::factory()->customer()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'username' => 'testcustomer',
        ]);

        // Create additional random users
        User::factory(10)->customer()->create();
        User::factory(5)->wholeseller()->create();

        $this->call([
            AdminUserSeeder::class,
            VendorSeeder::class,
            TestDataSeeder::class,
        ]);
    }
}
