<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasswordBreachService
{
    private const HIBP_API_URL = 'https://api.pwnedpasswords.com/range/';
    private const USER_AGENT = 'Laravel-Vue-Admin-Dashboard/1.0';

    /**
     * Check if a password has been compromised in data breaches
     */
    public function isPasswordCompromised(string $password): bool
    {
        try {
            $sha1Hash = strtoupper(sha1($password));
            $prefix = substr($sha1Hash, 0, 5);
            $suffix = substr($sha1Hash, 5);

            $response = Http::withHeaders([
                'User-Agent' => self::USER_AGENT,
                'Add-Padding' => 'true'
            ])->get(self::HIBP_API_URL . $prefix);

            if ($response->successful()) {
                $lines = explode("\n", $response->body());
                
                foreach ($lines as $line) {
                    if (empty(trim($line))) continue;
                    
                    [$hashSuffix, $count] = explode(':', $line);
                    
                    if (strtoupper($hashSuffix) === $suffix) {
                        Log::info('Password breach detected', [
                            'hash_suffix' => $hashSuffix,
                            'breach_count' => (int) $count
                        ]);
                        
                        return true;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Password breach check failed', [
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }

    /**
     * Get breach count for a password
     */
    public function getBreachCount(string $password): int
    {
        try {
            $sha1Hash = strtoupper(sha1($password));
            $prefix = substr($sha1Hash, 0, 5);
            $suffix = substr($sha1Hash, 5);

            $response = Http::withHeaders([
                'User-Agent' => self::USER_AGENT,
                'Add-Padding' => 'true'
            ])->get(self::HIBP_API_URL . $prefix);

            if ($response->successful()) {
                $lines = explode("\n", $response->body());
                
                foreach ($lines as $line) {
                    if (empty(trim($line))) continue;
                    
                    [$hashSuffix, $count] = explode(':', $line);
                    
                    if (strtoupper($hashSuffix) === $suffix) {
                        return (int) $count;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Password breach count check failed', [
                'error' => $e->getMessage()
            ]);
        }

        return 0;
    }

    /**
     * Get password strength score (0-100)
     */
    public function getPasswordStrength(string $password): array
    {
        $score = 0;
        $feedback = [];

        // Length check
        if (strlen($password) >= 12) {
            $score += 25;
        } elseif (strlen($password) >= 8) {
            $score += 15;
            $feedback[] = 'Consider using a longer password (12+ characters)';
        } else {
            $feedback[] = 'Password is too short (minimum 8 characters)';
        }

        // Character variety checks
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score += 15;

        // Missing character types
        if (!preg_match('/[a-z]/', $password)) {
            $feedback[] = 'Add lowercase letters';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $feedback[] = 'Add uppercase letters';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $feedback[] = 'Add numbers';
        }
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $feedback[] = 'Add special characters';
        }

        // Check for common patterns
        if (preg_match('/(.)\1{2,}/', $password)) {
            $score -= 10;
            $feedback[] = 'Avoid repeated characters';
        }

        if (preg_match('/(123|abc|qwe|password|admin)/i', $password)) {
            $score -= 20;
            $feedback[] = 'Avoid common patterns and words';
        }

        // Check for breach
        if ($this->isPasswordCompromised($password)) {
            $score -= 30;
            $feedback[] = 'This password has been found in data breaches';
        }

        // Ensure score is between 0 and 100
        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'feedback' => $feedback,
            'strength' => $this->getStrengthLabel($score),
            'breach_count' => $this->getBreachCount($password)
        ];
    }

    /**
     * Get strength label based on score
     */
    private function getStrengthLabel(int $score): string
    {
        if ($score >= 80) return 'Very Strong';
        if ($score >= 60) return 'Strong';
        if ($score >= 40) return 'Moderate';
        if ($score >= 20) return 'Weak';
        return 'Very Weak';
    }
}
