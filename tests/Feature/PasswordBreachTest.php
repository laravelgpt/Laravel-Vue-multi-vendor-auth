<?php

namespace Tests\Feature;

use App\Services\PasswordBreachService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PasswordBreachTest extends TestCase
{
    use RefreshDatabase;

    private PasswordBreachService $passwordBreachService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->passwordBreachService = app(PasswordBreachService::class);
        Cache::flush();
    }

    public function test_password_breach_check_with_breached_password(): void
    {
        // Mock a breached password response
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response(
                "0000000CAEF40505D81471EB4C4F70D451C:1\n" .
                "0000000D0FEDA09F3B05CF727A65B415F2C:2\n" .
                "0000000E1B4F2B857C365A645EB5918175C:3\n",
                200
            ),
        ]);

        // Test with a known weak password
        $result = $this->passwordBreachService->checkPasswordBreach('password123');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('breached', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertArrayHasKey('message', $result);
    }

    public function test_password_breach_check_with_safe_password(): void
    {
        // Mock a safe password response (no breaches found)
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response(
                "0000000CAEF40505D81471EB4C4F70D451C:1\n" .
                "0000000D0FEDA09F3B05CF727A65B415F2C:2\n",
                200
            ),
        ]);

        // Test with a strong, unique password
        $result = $this->passwordBreachService->checkPasswordBreach('MySecureP@ssw0rd!2024');

        $this->assertIsArray($result);
        $this->assertFalse($result['breached']);
        $this->assertEquals(0, $result['count']);
        $this->assertStringContainsString('not been found', $result['message']);
    }

    public function test_password_breach_check_api_failure(): void
    {
        // Mock API failure
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('', 500),
        ]);

        $result = $this->passwordBreachService->checkPasswordBreach('testpassword');

        $this->assertIsArray($result);
        $this->assertFalse($result['breached']);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('Unable to check', $result['error']);
    }

    public function test_password_breach_check_timeout(): void
    {
        // Mock timeout
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('', 408),
        ]);

        $result = $this->passwordBreachService->checkPasswordBreach('testpassword');

        $this->assertIsArray($result);
        $this->assertFalse($result['breached']);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_password_strength_calculation(): void
    {
        $testCases = [
            '123' => ['level' => 'very-weak', 'score' => 0],
            'password' => ['level' => 'weak', 'score' => 20],
            'Password123' => ['level' => 'weak', 'score' => 30],
            'Password123!' => ['level' => 'fair', 'score' => 60],
            'MySecureP@ssw0rd!2024' => ['level' => 'good', 'score' => 75],
        ];

        foreach ($testCases as $password => $expected) {
            $result = $this->passwordBreachService->getPasswordStrength($password);
            
            $this->assertEquals($expected['level'], $result['level']);
            $this->assertGreaterThanOrEqual($expected['score'], $result['score']);
            $this->assertArrayHasKey('requirements', $result);
            $this->assertArrayHasKey('message', $result);
        }
    }

    public function test_password_strength_requirements(): void
    {
        $result = $this->passwordBreachService->getPasswordStrength('Password123!');

        $this->assertArrayHasKey('requirements', $result);
        $requirements = $result['requirements'];

        $this->assertTrue($requirements['length']['met']);
        $this->assertTrue($requirements['lowercase']['met']);
        $this->assertTrue($requirements['uppercase']['met']);
        $this->assertTrue($requirements['number']['met']);
        $this->assertTrue($requirements['special']['met']);
    }

    public function test_password_validation_comprehensive(): void
    {
        // Test with a strong, non-breached password
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('', 200),
        ]);

        $result = $this->passwordBreachService->validatePassword('MySecureP@ssw0rd!2024');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('valid', $result);
        $this->assertArrayHasKey('strength', $result);
        $this->assertArrayHasKey('breach', $result);
        $this->assertArrayHasKey('recommendations', $result);

        $this->assertTrue($result['valid']);
        $this->assertFalse($result['breach']['breached']);
        $this->assertGreaterThanOrEqual(70, $result['strength']['score']);
    }

    public function test_password_validation_weak_password(): void
    {
        $result = $this->passwordBreachService->validatePassword('123');

        $this->assertFalse($result['valid']);
        $this->assertLessThan(50, $result['strength']['score']);
        $this->assertNotEmpty($result['recommendations']);
    }

    public function test_password_validation_breached_password(): void
    {
        // Get the actual hash for password123
        $hash = strtoupper(sha1('password123'));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);
        
        // Mock a breached password response with the correct hash
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response(
                $suffix . ":1\n",
                200
            ),
        ]);

        $result = $this->passwordBreachService->validatePassword('password123');

        $this->assertFalse($result['valid']);
        $this->assertTrue($result['breach']['breached']);
        $this->assertStringContainsString('compromised', $result['recommendations'][0]);
    }

    public function test_password_breach_caching(): void
    {
        // Mock API response
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response(
                "0000000CAEF40505D81471EB4C4F70D451C:1\n",
                200
            ),
        ]);

        // First call should hit the API
        $result1 = $this->passwordBreachService->checkPasswordBreach('password123');
        
        // Second call should use cache
        $result2 = $this->passwordBreachService->checkPasswordBreach('password123');

        $this->assertEquals($result1, $result2);
        
        // Verify only one HTTP request was made
        Http::assertSentCount(1);
    }

    public function test_password_breach_parse_response(): void
    {
        $response = "0000000CAEF40505D81471EB4C4F70D451C:1\n" .
                   "0000000D0FEDA09F3B05CF727A65B415F2C:2\n" .
                   "0000000E1B4F2B857C365A645EB5918175C:3\n";

        $reflection = new \ReflectionClass($this->passwordBreachService);
        $method = $reflection->getMethod('parseBreachedHashes');
        $method->setAccessible(true);

        $result = $method->invoke($this->passwordBreachService, $response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['0000000CAEF40505D81471EB4C4F70D451C']);
        $this->assertEquals(2, $result['0000000D0FEDA09F3B05CF727A65B415F2C']);
        $this->assertEquals(3, $result['0000000E1B4F2B857C365A645EB5918175C']);
    }

    public function test_password_strength_penalties(): void
    {
        // Test repeated characters penalty
        $result1 = $this->passwordBreachService->getPasswordStrength('aaa123!');
        $result2 = $this->passwordBreachService->getPasswordStrength('abc123!');

        $this->assertLessThan($result2['score'], $result1['score']);

        // Test common sequences penalty
        $result3 = $this->passwordBreachService->getPasswordStrength('123456!');
        $result4 = $this->passwordBreachService->getPasswordStrength('random!');

        $this->assertLessThan($result4['score'], $result3['score']);
    }

    public function test_password_recommendations(): void
    {
        $result = $this->passwordBreachService->validatePassword('weak');

        $this->assertIsArray($result['recommendations']);
        $this->assertNotEmpty($result['recommendations']);
        // Check that recommendations contain improvement suggestions
        $hasWeakRecommendation = false;
        foreach ($result['recommendations'] as $recommendation) {
            if (str_contains(strtolower($recommendation), 'weak') || 
                str_contains(strtolower($recommendation), 'improvement') ||
                str_contains(strtolower($recommendation), 'characters')) {
                $hasWeakRecommendation = true;
                break;
            }
        }
        $this->assertTrue($hasWeakRecommendation, 'Should contain recommendations for weak password');
    }

    public function test_empty_password_handling(): void
    {
        $result = $this->passwordBreachService->checkPasswordBreach('');
        
        $this->assertFalse($result['breached']);
        $this->assertEquals(0, $result['count']);
    }

    public function test_special_characters_in_password(): void
    {
        $specialChars = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '='];
        
        foreach ($specialChars as $char) {
            $password = 'Password123' . $char;
            $result = $this->passwordBreachService->getPasswordStrength($password);
            
            $this->assertTrue($result['requirements']['special']['met'], "Special character '{$char}' should be recognized");
        }
    }
}
