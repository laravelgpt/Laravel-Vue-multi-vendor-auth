<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RollbackRoleMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rollback:role-migration {--steps=1 : Number of migration steps to rollback}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the role-based migration to revert to the old user role system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $steps = $this->option('steps');

        $this->warn('This will rollback the role-based migration and revert to the old user role system.');

        if (! $this->confirm('Are you sure you want to continue?')) {
            $this->info('Operation cancelled.');

            return;
        }

        $this->info("Rolling back {$steps} migration step(s)...");

        Artisan::call('migrate:rollback', ['--step' => $steps]);

        $this->info('Migration rollback completed.');
        $this->warn('Note: You may need to update your code to work with the old role system.');
    }
}
