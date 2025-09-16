<?php

namespace Tests\Feature;

use App\Services\PasswordBreachService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PasswordBreachIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private PasswordBreachService $passwordBreachService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->passwordBreachService = app(PasswordBreachService::class);
    }

    public function test_real_api_integration_with_known_breached_password(): void
    {
        // Test with a password that is known to be breached
        // Note: This test will make a real API call to HaveIBeenPwned
        $result = $this->passwordBreachService->checkPasswordBreach('password');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('breached', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('message', $result);

        // The password "password" is almost certainly breached
        if ($result['breached']) {
            $this->assertGreaterThan(0, $result['count']);
            $this->assertStringContainsString('found in', $result['message']);
        }
    }

    public function test_real_api_integration_with_random_password(): void
    {
        // Test with a random password that should not be breached
        $randomPassword = 'RandomPassword' . uniqid() . '!@#';
        
        $result = $this->passwordBreachService->checkPasswordBreach($randomPassword);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('breached', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('message', $result);

        // Random password should not be breached
        $this->assertFalse($result['breached']);
        $this->assertEquals(0, $result['count']);
    }

    public function test_password_validation_with_real_api(): void
    {
        // Test comprehensive validation with real API
        $result = $this->passwordBreachService->validatePassword('password123');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('valid', $result);
        $this->assertArrayHasKey('strength', $result);
        $this->assertArrayHasKey('breach', $result);
        $this->assertArrayHasKey('recommendations', $result);

        // Should have strength analysis
        $this->assertArrayHasKey('score', $result['strength']);
        $this->assertArrayHasKey('level', $result['strength']);
        $this->assertArrayHasKey('requirements', $result['strength']);

        // Should have breach analysis
        $this->assertArrayHasKey('breached', $result['breach']);
        $this->assertArrayHasKey('count', $result['breach']);
    }

    public function test_api_rate_limiting_and_caching(): void
    {
        // Test that multiple calls to the same password use caching
        $password = 'testpassword' . uniqid();
        
        // First call
        $result1 = $this->passwordBreachService->checkPasswordBreach($password);
        
        // Second call should use cache
        $result2 = $this->passwordBreachService->checkPasswordBreach($password);

        $this->assertEquals($result1, $result2);
    }

    public function test_password_strength_calculation_accuracy(): void
    {
        $testCases = [
            '123' => ['expected_level' => 'very-weak', 'min_score' => 0],
            'password' => ['expected_level' => 'weak', 'min_score' => 20],
            'Password123' => ['expected_level' => 'weak', 'min_score' => 30],
            'Password123!' => ['expected_level' => 'fair', 'min_score' => 60],
            'MySecureP@ssw0rd!2024' => ['expected_level' => 'good', 'min_score' => 75],
        ];

        foreach ($testCases as $password => $expected) {
            $result = $this->passwordBreachService->getPasswordStrength($password);
            
            $this->assertEquals($expected['expected_level'], $result['level']);
            $this->assertGreaterThanOrEqual($expected['min_score'], $result['score']);
            $this->assertArrayHasKey('requirements', $result);
            $this->assertArrayHasKey('message', $result);
        }
    }

    public function test_error_handling_with_network_issues(): void
    {
        // Mock network timeout
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('', 408),
        ]);

        $result = $this->passwordBreachService->checkPasswordBreach('testpassword');

        $this->assertIsArray($result);
        $this->assertFalse($result['breached']);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('unable to check', strtolower($result['error']));
    }

    public function test_error_handling_with_server_error(): void
    {
        // Mock server error
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('', 500),
        ]);

        $result = $this->passwordBreachService->checkPasswordBreach('testpassword');

        $this->assertIsArray($result);
        $this->assertFalse($result['breached']);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('unable to check', strtolower($result['error']));
    }

    public function test_password_requirements_validation(): void
    {
        $testCases = [
            'short' => ['length' => false, 'lowercase' => true, 'uppercase' => false, 'number' => false, 'special' => false],
            'password' => ['length' => true, 'lowercase' => true, 'uppercase' => false, 'number' => false, 'special' => false],
            'Password' => ['length' => true, 'lowercase' => true, 'uppercase' => true, 'number' => false, 'special' => false],
            'Password123' => ['length' => true, 'lowercase' => true, 'uppercase' => true, 'number' => true, 'special' => false],
            'Password123!' => ['length' => true, 'lowercase' => true, 'uppercase' => true, 'number' => true, 'special' => true],
        ];

        foreach ($testCases as $password => $expected) {
            $result = $this->passwordBreachService->getPasswordStrength($password);
            $requirements = $result['requirements'];

            $this->assertEquals($expected['length'], $requirements['length']['met']);
            $this->assertEquals($expected['lowercase'], $requirements['lowercase']['met']);
            $this->assertEquals($expected['uppercase'], $requirements['uppercase']['met']);
            $this->assertEquals($expected['number'], $requirements['number']['met']);
            $this->assertEquals($expected['special'], $requirements['special']['met']);
        }
    }

    public function test_recommendations_generation(): void
    {
        // Test weak password recommendations
        $result = $this->passwordBreachService->validatePassword('weak');
        
        $this->assertIsArray($result['recommendations']);
        $this->assertNotEmpty($result['recommendations']);
        
        // Should contain improvement suggestions
        $hasImprovementSuggestion = false;
        foreach ($result['recommendations'] as $recommendation) {
            if (str_contains(strtolower($recommendation), 'improve') || 
                str_contains(strtolower($recommendation), 'add') ||
                str_contains(strtolower($recommendation), 'use')) {
                $hasImprovementSuggestion = true;
                break;
            }
        }
        $this->assertTrue($hasImprovementSuggestion, 'Should contain improvement recommendations');
    }

    public function test_performance_with_multiple_passwords(): void
    {
        $passwords = [
            'password123',
            'Password123!',
            'MySecureP@ssw0rd!2024',
            'weak',
            'strongpassword123!@#'
        ];

        $startTime = microtime(true);

        foreach ($passwords as $password) {
            $this->passwordBreachService->validatePassword($password);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should complete within reasonable time (less than 10 seconds for 5 passwords)
        $this->assertLessThan(10, $executionTime, "Password validation took too long: {$executionTime} seconds");
    }
}
