<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some vendors with existing users (wholesellers and customers)
        $users = User::whereIn('role', ['wholeseller', 'customer'])->take(10)->get();

        foreach ($users as $user) {
            Vendor::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name.' Services',
            ]);
        }

        // Create additional vendors
        Vendor::factory(15)->create();
    }
}
