<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PasswordBreachService
{
    private const HIBP_API_URL = 'https://api.pwnedpasswords.com/range/';
    private const CACHE_PREFIX = 'password_breach_';
    private const CACHE_TTL = 3600; // 1 hour

    /**
     * Check if a password has been breached using HaveIBeenPwned API
     */
    public function checkPasswordBreach(string $password): array
    {
        try {
            // Handle empty password
            if (empty($password)) {
                return [
                    'breached' => false,
                    'count' => 0,
                    'message' => 'Password is empty'
                ];
            }

            $hash = strtoupper(sha1($password));
            $prefix = substr($hash, 0, 5);
            $suffix = substr($hash, 5);

            // Check cache first
            $cacheKey = self::CACHE_PREFIX . $prefix;
            $breachedHashes = Cache::get($cacheKey);

            if ($breachedHashes === null) {
                // Fetch from API
                $response = Http::timeout(5)->get(self::HIBP_API_URL . $prefix);
                
                if ($response->successful()) {
                    $breachedHashes = $this->parseBreachedHashes($response->body());
                    Cache::put($cacheKey, $breachedHashes, self::CACHE_TTL);
                } else {
                    Log::warning('Password breach API request failed', [
                        'status' => $response->status(),
                        'prefix' => $prefix
                    ]);
                    return [
                        'breached' => false,
                        'count' => 0,
                        'error' => 'Unable to check password security at this time'
                    ];
                }
            }

            // Check if our password suffix is in the breached list
            if (isset($breachedHashes[$suffix])) {
                return [
                    'breached' => true,
                    'count' => $breachedHashes[$suffix],
                    'message' => "This password has been found in {$breachedHashes[$suffix]} data breaches"
                ];
            }

            return [
                'breached' => false,
                'count' => 0,
                'message' => 'Password has not been found in any known breaches'
            ];

        } catch (\Exception $e) {
            Log::error('Password breach check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'breached' => false,
                'count' => 0,
                'error' => 'Unable to check password security at this time'
            ];
        }
    }

    /**
     * Parse the response from HaveIBeenPwned API
     */
    private function parseBreachedHashes(string $response): array
    {
        $hashes = [];
        $lines = explode("\n", $response);

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            $parts = explode(':', trim($line));
            if (count($parts) === 2) {
                $hashes[$parts[0]] = (int) $parts[1];
            }
        }

        return $hashes;
    }

    /**
     * Get password strength score based on multiple factors
     */
    public function getPasswordStrength(string $password): array
    {
        $score = 0;
        $maxScore = 100;
        $requirements = [];

        // Length check
        $length = strlen($password);
        if ($length >= 8) {
            $score += 20;
            $requirements['length'] = ['met' => true, 'text' => 'At least 8 characters'];
        } else {
            $requirements['length'] = ['met' => false, 'text' => 'At least 8 characters'];
        }

        if ($length >= 12) {
            $score += 10;
        }

        // Character variety checks
        if (preg_match('/[a-z]/', $password)) {
            $score += 10;
            $requirements['lowercase'] = ['met' => true, 'text' => 'Contains lowercase letter'];
        } else {
            $requirements['lowercase'] = ['met' => false, 'text' => 'Contains lowercase letter'];
        }

        if (preg_match('/[A-Z]/', $password)) {
            $score += 10;
            $requirements['uppercase'] = ['met' => true, 'text' => 'Contains uppercase letter'];
        } else {
            $requirements['uppercase'] = ['met' => false, 'text' => 'Contains uppercase letter'];
        }

        if (preg_match('/\d/', $password)) {
            $score += 10;
            $requirements['number'] = ['met' => true, 'text' => 'Contains number'];
        } else {
            $requirements['number'] = ['met' => false, 'text' => 'Contains number'];
        }

        if (preg_match('/[^a-zA-Z\d]/', $password)) {
            $score += 15;
            $requirements['special'] = ['met' => true, 'text' => 'Contains special character'];
        } else {
            $requirements['special'] = ['met' => false, 'text' => 'Contains special character'];
        }

        // Common patterns penalty
        if (preg_match('/(.)\1{2,}/', $password)) {
            $score -= 10; // Repeated characters
        }

        if (preg_match('/123|abc|qwe|asd|zxc/i', $password)) {
            $score -= 15; // Common sequences
        }

        // Determine strength level
        $level = match (true) {
            $score < 30 => 'very-weak',
            $score < 50 => 'weak',
            $score < 70 => 'fair',
            $score < 85 => 'good',
            default => 'strong'
        };

        $messages = [
            'very-weak' => 'Very weak password',
            'weak' => 'Weak password',
            'fair' => 'Fair password',
            'good' => 'Good password',
            'strong' => 'Strong password'
        ];

        return [
            'score' => max(0, min($maxScore, $score)),
            'maxScore' => $maxScore,
            'level' => $level,
            'message' => $messages[$level],
            'requirements' => $requirements
        ];
    }

    /**
     * Comprehensive password validation
     */
    public function validatePassword(string $password): array
    {
        $strength = $this->getPasswordStrength($password);
        $breach = $this->checkPasswordBreach($password);

        $isValid = $strength['score'] >= 50 && !$breach['breached'];

        return [
            'valid' => $isValid,
            'strength' => $strength,
            'breach' => $breach,
            'recommendations' => $this->getRecommendations($strength, $breach)
        ];
    }

    /**
     * Get password improvement recommendations
     */
    private function getRecommendations(array $strength, array $breach): array
    {
        $recommendations = [];

        if ($breach['breached']) {
            $recommendations[] = 'This password has been compromised. Please choose a different password.';
        }

        if ($strength['score'] < 50) {
            $recommendations[] = 'Password is too weak. Consider adding more characters and variety.';
        }

        if (!$strength['requirements']['length']['met']) {
            $recommendations[] = 'Use at least 8 characters.';
        }

        if (!$strength['requirements']['uppercase']['met']) {
            $recommendations[] = 'Add uppercase letters.';
        }

        if (!$strength['requirements']['lowercase']['met']) {
            $recommendations[] = 'Add lowercase letters.';
        }

        if (!$strength['requirements']['number']['met']) {
            $recommendations[] = 'Add numbers.';
        }

        if (!$strength['requirements']['special']['met']) {
            $recommendations[] = 'Add special characters (!@#$%^&*).';
        }

        return $recommendations;
    }
}
