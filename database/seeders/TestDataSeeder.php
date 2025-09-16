<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with different roles
        $admin = User::factory()->admin()->create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'admin@test.com',
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'user@test.com',
        ]);

        // Create additional users
        User::factory(10)->create();

        // Create vendors for some users
        Vendor::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name.' Services',
        ]);

        // Create additional vendors
        Vendor::factory(5)->create();
    }
}
