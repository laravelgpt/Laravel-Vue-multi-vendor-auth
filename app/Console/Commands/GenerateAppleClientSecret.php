<?php

namespace App\Console\Commands;

use Firebase\JWT\JWT;
use Illuminate\Console\Command;

class GenerateAppleClientSecret extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apple:generate-secret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Apple Sign-In client secret';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if required environment variables are set
        $requiredVars = ['APPLE_TEAM_ID', 'APPLE_CLIENT_ID', 'APPLE_KEY_ID', 'APPLE_PRIVATE_KEY'];

        foreach ($requiredVars as $var) {
            if (! env($var)) {
                $this->error("Missing required environment variable: {$var}");
                $this->info("Please set {$var} in your .env file first.");

                return 1;
            }
        }

        $this->info('Generating Apple Sign-In client secret...');

        $payload = [
            'iss' => env('APPLE_TEAM_ID'),
            'iat' => time(),
            'exp' => time() + 86400 * 180, // 6 months
            'aud' => 'https://appleid.apple.com',
            'sub' => env('APPLE_CLIENT_ID'),
        ];

        try {
            $client_secret = JWT::encode($payload, env('APPLE_PRIVATE_KEY'), 'ES256', env('APPLE_KEY_ID'));

            $this->info('✅ Apple Client Secret generated successfully!');
            $this->newLine();
            $this->info('📋 Copy this to your .env file as APPLE_CLIENT_SECRET:');
            $this->newLine();
            $this->line($client_secret);
            $this->newLine();
            $this->info('⚠️  This secret is valid for 6 months. Remember to regenerate it before expiration.');

        } catch (\Exception $e) {
            $this->error('❌ Failed to generate client secret: '.$e->getMessage());
            $this->info('Please check your APPLE_PRIVATE_KEY format and other environment variables.');

            return 1;
        }

        return 0;
    }
}
