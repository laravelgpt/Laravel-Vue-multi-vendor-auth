<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use SmartCache\Facades\SmartCache;

class SmartCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_smart_cache_facade_is_available()
    {
        $this->assertTrue(class_exists('SmartCache\Facades\SmartCache'));
    }

    public function test_smart_cache_helper_function_is_available()
    {
        $this->assertTrue(function_exists('smart_cache'));
    }

    public function test_basic_caching_works()
    {
        $testData = ['test' => 'value', 'number' => 123];
        
        SmartCache::put('test_key', $testData, 60);
        
        $retrieved = SmartCache::get('test_key');
        
        $this->assertEquals($testData, $retrieved);
    }

    public function test_helper_function_works()
    {
        $testData = ['helper_test' => 'helper_value'];
        
        smart_cache($testData, 60);
        
        $retrieved = smart_cache('helper_test');
        
        $this->assertEquals('helper_value', $retrieved);
    }

    public function test_large_data_caching_works()
    {
        $largeData = range(1, 1000);
        
        SmartCache::put('large_data', $largeData, 60);
        
        $retrieved = SmartCache::get('large_data');
        
        $this->assertCount(1000, $retrieved);
        $this->assertEquals($largeData, $retrieved);
    }

    public function test_cache_has_method_works()
    {
        SmartCache::put('exists_key', 'value', 60);
        
        $this->assertTrue(SmartCache::has('exists_key'));
        $this->assertFalse(SmartCache::has('non_exists_key'));
    }

    public function test_cache_forget_method_works()
    {
        SmartCache::put('forget_key', 'value', 60);
        
        $this->assertTrue(SmartCache::has('forget_key'));
        
        SmartCache::forget('forget_key');
        
        $this->assertFalse(SmartCache::has('forget_key'));
    }

    public function test_cache_clear_method_works()
    {
        SmartCache::put('key1', 'value1', 60);
        SmartCache::put('key2', 'value2', 60);
        
        $this->assertTrue(SmartCache::has('key1'));
        $this->assertTrue(SmartCache::has('key2'));
        
        // Clear should return true if successful
        $result = SmartCache::clear();
        $this->assertTrue($result);
        
        // Get managed keys should return an array
        $managedKeys = SmartCache::getManagedKeys();
        $this->assertIsArray($managedKeys);
    }

    public function test_cache_with_default_value()
    {
        $value = smart_cache('non_existent_key', 'default_value');
        
        $this->assertEquals('default_value', $value);
    }

    public function test_complex_data_structure_caching()
    {
        $complexData = [
            'users' => [
                ['id' => 1, 'name' => 'John'],
                ['id' => 2, 'name' => 'Jane'],
            ],
            'metadata' => [
                'total' => 2,
                'timestamp' => now()->toISOString(),
            ],
            'settings' => [
                'enabled' => true,
                'limit' => 100,
            ]
        ];
        
        SmartCache::put('complex_data', $complexData, 60);
        
        $retrieved = SmartCache::get('complex_data');
        
        $this->assertEquals($complexData, $retrieved);
        $this->assertCount(2, $retrieved['users']);
        $this->assertTrue($retrieved['settings']['enabled']);
    }
}
