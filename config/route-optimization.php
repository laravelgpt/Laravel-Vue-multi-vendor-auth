<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for route optimization
    | and performance improvements.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Route Caching
    |--------------------------------------------------------------------------
    |
    | Enable route caching for better performance in production
    |
    */
    'cache_routes' => env('ROUTE_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Model Binding
    |--------------------------------------------------------------------------
    |
    | Configuration for route model binding optimization
    |
    */
    'model_binding' => [
        'enabled' => true,
        'cache_models' => true,
        'lazy_loading' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Global rate limiting configuration for different route groups
    |
    */
    'rate_limiting' => [
        'api' => [
            'enabled' => true,
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'auth' => [
            'enabled' => true,
            'max_attempts' => 5,
            'decay_minutes' => 1,
        ],
        'admin' => [
            'enabled' => true,
            'max_attempts' => 100,
            'decay_minutes' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Optimization
    |--------------------------------------------------------------------------
    |
    | Configuration for middleware optimization
    |
    */
    'middleware' => [
        'optimize' => true,
        'cache_middleware' => true,
        'lazy_load' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Groups
    |--------------------------------------------------------------------------
    |
    | Configuration for route group optimization
    |
    */
    'groups' => [
        'web' => [
            'middleware' => ['web'],
            'prefix' => '',
            'namespace' => 'App\\Http\\Controllers',
        ],
        'api' => [
            'middleware' => ['api'],
            'prefix' => 'api',
            'namespace' => 'App\\Http\\Controllers\\Api',
        ],
        'admin' => [
            'middleware' => ['auth', 'admin'],
            'prefix' => 'admin',
            'namespace' => 'App\\Http\\Controllers\\Admin',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Configuration for route performance monitoring
    |
    */
    'monitoring' => [
        'enabled' => env('ROUTE_MONITORING_ENABLED', false),
        'log_slow_routes' => true,
        'slow_route_threshold' => 1000, // milliseconds
        'log_file' => 'route-performance.log',
    ],
];
