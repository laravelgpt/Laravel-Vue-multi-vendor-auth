<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FreshRoleBasedSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:role-based {--fresh : Run fresh migrations first}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with role-based data (admin, wholeseller, customer)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Running fresh migrations...');
            Artisan::call('migrate:fresh');
            $this->info('Migrations completed.');
        }

        $this->info('Seeding role-based data...');

        // Run the role-based seeder
        Artisan::call('db:seed', ['--class' => 'RoleBasedDataSeeder']);

        $this->info('Role-based data seeded successfully!');

        // Display login credentials
        $this->newLine();
        $this->info('Login Credentials:');
        $this->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@example.com', 'password'],
                ['Admin', 'admin@example.com', 'password'],
                ['Wholeseller', 'wholeseller@example.com', 'password'],
                ['Customer', 'customer@example.com', 'password'],
            ]
        );

        $this->newLine();
        $this->info('You can now test the role-based access control system!');
    }
}
