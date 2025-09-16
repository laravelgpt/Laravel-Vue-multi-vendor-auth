<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'country_code' => '+1',
            'address' => '123 Admin Street',
            'country' => 'United States',
            'state' => 'California',
            'city' => 'San Francisco',
            'zip_code' => '94105',
            'role' => 'admin',
            'is_active' => true,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Wholeseller User',
            'username' => 'wholeseller',
            'email' => 'wholeseller@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'country_code' => '+1',
            'address' => '456 Wholeseller Avenue',
            'country' => 'United States',
            'state' => 'New York',
            'city' => 'New York',
            'zip_code' => '10001',
            'role' => 'wholeseller',
            'is_active' => true,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Customer User',
            'username' => 'customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
            'country_code' => '+1',
            'address' => '789 Customer Street',
            'country' => 'United States',
            'state' => 'Texas',
            'city' => 'Houston',
            'zip_code' => '77001',
            'role' => 'customer',
            'is_active' => true,
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}
