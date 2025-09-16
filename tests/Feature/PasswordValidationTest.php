<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Auth\Register;

class PasswordValidationTest extends TestCase
{
    public function test_password_strength_calculation_weak(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', '123');
        
        $strength = $component->get('passwordStrength');
        
        $this->assertEquals('weak', $strength['level']);
        $this->assertEquals(1, $strength['score']); // Only number requirement met
        $this->assertEquals(5, $strength['total']);
        $this->assertEquals('Password is too weak', $strength['message']);
    }

    public function test_password_strength_calculation_fair(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'password123');
        
        $strength = $component->get('passwordStrength');
        
        $this->assertEquals('medium', $strength['level']); // length + lowercase + number = 3
        $this->assertEquals(3, $strength['score']);
        $this->assertEquals(5, $strength['total']);
        $this->assertEquals('Password is medium strength', $strength['message']);
    }

    public function test_password_strength_calculation_medium(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123');
        
        $strength = $component->get('passwordStrength');
        
        $this->assertEquals('fair', $strength['level']); // length + lowercase + uppercase + number = 4, but we need <= 3 for medium
        $this->assertEquals(4, $strength['score']);
        $this->assertEquals(5, $strength['total']);
        $this->assertEquals('Password is fair', $strength['message']);
    }

    public function test_password_strength_calculation_strong(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        
        $strength = $component->get('passwordStrength');
        
        $this->assertEquals('strong', $strength['level']);
        $this->assertEquals(5, $strength['score']);
        $this->assertEquals(5, $strength['total']);
        $this->assertEquals('Password is strong', $strength['message']);
    }

    public function test_password_requirements_checklist(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        
        $validation = $component->get('passwordValidation');
        $requirements = $validation['strength']['requirements'];
        
        $this->assertCount(5, $requirements);
        
        // Check all requirements are met
        foreach ($requirements as $requirement) {
            $this->assertTrue($requirement['met']);
        }
        
        // Check requirement texts
        $texts = array_column($requirements, 'text');
        $this->assertContains('At least 8 characters', $texts);
        $this->assertContains('Contains lowercase letter', $texts);
        $this->assertContains('Contains uppercase letter', $texts);
        $this->assertContains('Contains number', $texts);
        $this->assertContains('Contains special character', $texts);
    }

    public function test_password_requirements_partial_fulfillment(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'password'); // Only lowercase and length
        
        $validation = $component->get('passwordValidation');
        $requirements = $validation['strength']['requirements'];
        
        $this->assertEquals(30, $validation['strength']['score']);
        
        // Check specific requirements
        $lengthReq = collect($requirements)->firstWhere('text', 'At least 8 characters');
        $lowerReq = collect($requirements)->firstWhere('text', 'Contains lowercase letter');
        $upperReq = collect($requirements)->firstWhere('text', 'Contains uppercase letter');
        $numberReq = collect($requirements)->firstWhere('text', 'Contains number');
        $specialReq = collect($requirements)->firstWhere('text', 'Contains special character');
        
        $this->assertTrue($lengthReq['met']);
        $this->assertTrue($lowerReq['met']);
        $this->assertFalse($upperReq['met']);
        $this->assertFalse($numberReq['met']);
        $this->assertFalse($specialReq['met']);
    }

    public function test_password_match_validation(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'Password123!');
        
        $this->assertTrue($component->get('passwordMatch'));
    }

    public function test_password_mismatch_validation(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'DifferentPassword123!');
        
        $this->assertFalse($component->get('passwordMatch'));
    }

    public function test_password_match_null_when_confirmation_empty(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', '');
        
        $this->assertNull($component->get('passwordMatch'));
    }

    public function test_password_strength_empty_password(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', '');
        
        $strength = $component->get('passwordStrength');
        
        $this->assertEquals('none', $strength['level']);
        $this->assertEquals(0, $strength['score']);
        $this->assertEquals('', $strength['message']);
        $this->assertEmpty($strength['requirements']);
    }

    public function test_password_validation_rules(): void
    {
        $component = Livewire::test(Register::class);
        
        // Test weak password
        $component->set('password', '123');
        $component->set('password_confirmation', '123');
        
        $component->call('register');
        
        $component->assertHasErrors(['password']);
    }

    public function test_password_confirmation_validation(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'DifferentPassword123!');
        
        $component->call('register');
        
        $component->assertHasErrors(['password']);
    }

    public function test_strong_password_passes_validation(): void
    {
        $component = Livewire::test(Register::class);
        
        $component->set('name', 'Test User');
        $component->set('username', 'testuser');
        $component->set('email', 'test@example.com');
        $component->set('password', 'Password123!');
        $component->set('password_confirmation', 'Password123!');
        
        $component->call('register');
        
        $component->assertHasNoErrors(['password']);
    }

    public function test_special_characters_validation(): void
    {
        $component = Livewire::test(Register::class);
        
        // Test with different special characters
        $specialChars = ['@', '$', '!', '%', '*', '?', '&'];
        
        foreach ($specialChars as $char) {
            $password = 'Password123' . $char;
            $component->set('password', $password);
            
            $validation = $component->get('passwordValidation');
            $specialReq = collect($validation['strength']['requirements'])->firstWhere('text', 'Contains special character');
            
            $this->assertTrue($specialReq['met'], "Special character '{$char}' should be recognized");
        }
    }

    public function test_password_length_edge_cases(): void
    {
        $component = Livewire::test(Register::class);
        
        // Test exactly 8 characters
        $component->set('password', 'Pass123!');
        $validation = $component->get('passwordValidation');
        $lengthReq = collect($validation['strength']['requirements'])->firstWhere('text', 'At least 8 characters');
        $this->assertTrue($lengthReq['met']);
        
        // Test 7 characters
        $component->set('password', 'Pass123');
        $validation = $component->get('passwordValidation');
        $lengthReq = collect($validation['strength']['requirements'])->firstWhere('text', 'At least 8 characters');
        $this->assertFalse($lengthReq['met']);
    }
}
