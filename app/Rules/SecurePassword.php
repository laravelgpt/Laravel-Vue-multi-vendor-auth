<?php

namespace App\Rules;

use App\Services\PasswordBreachService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

class SecurePassword implements ValidationRule
{
    private PasswordBreachService $breachService;
    private bool $checkBreach;

    public function __construct(bool $checkBreach = true)
    {
        $this->breachService = new PasswordBreachService();
        $this->checkBreach = $checkBreach;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('The :attribute must be a string.');
            return;
        }

        // Basic password requirements
        if (strlen($value) < 8) {
            $fail('The :attribute must be at least 8 characters.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must contain at least one lowercase letter.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('The :attribute must contain at least one uppercase letter.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('The :attribute must contain at least one number.');
            return;
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $fail('The :attribute must contain at least one symbol.');
            return;
        }

        // Additional strength check
        $strength = $this->breachService->getPasswordStrength($value);
        
        if ($strength['score'] < 40) {
            $fail('The :attribute is too weak. ' . implode(', ', $strength['feedback']));
        }

        // Check for breach if enabled
        if ($this->checkBreach && $this->breachService->isPasswordCompromised($value)) {
            $fail('The :attribute has been found in data breaches and cannot be used.');
        }
    }
}
