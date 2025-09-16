<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class OptimizeRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:optimize 
                            {--cache : Cache the routes for better performance}
                            {--clear : Clear route cache}
                            {--analyze : Analyze route performance}
                            {--export : Export route information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize routes for better performance and analyze route structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('clear')) {
            return $this->clearRouteCache();
        }

        if ($this->option('analyze')) {
            return $this->analyzeRoutes();
        }

        if ($this->option('export')) {
            return $this->exportRoutes();
        }

        if ($this->option('cache')) {
            return $this->cacheRoutes();
        }

        // Default: show optimization status
        return $this->showOptimizationStatus();
    }

    /**
     * Clear route cache
     */
    private function clearRouteCache(): int
    {
        $this->info('Clearing route cache...');

        Cache::forget('routes.optimized');
        $this->call('route:clear');

        $this->info('Route cache cleared successfully!');

        return 0;
    }

    /**
     * Cache routes for better performance
     */
    private function cacheRoutes(): int
    {
        $this->info('Caching routes for better performance...');

        $this->call('route:cache');

        $this->info('Routes cached successfully!');

        return 0;
    }

    /**
     * Analyze route performance and structure
     */
    private function analyzeRoutes(): int
    {
        $this->info('Analyzing route structure...');

        $routes = Route::getRoutes();
        $routeCount = $routes->count();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Routes', $routeCount],
                ['Web Routes', $this->countRoutesByPrefix('')],
                ['API Routes', $this->countRoutesByPrefix('api')],
                ['Admin Routes', $this->countRoutesByPrefix('admin')],
                ['Auth Routes', $this->countRoutesByPrefix('auth')],
            ]
        );

        $this->info("\nRoute Analysis Complete!");

        return 0;
    }

    /**
     * Export route information
     */
    private function exportRoutes(): int
    {
        $this->info('Exporting route information...');

        $routes = Route::getRoutes();
        $routeData = [];

        foreach ($routes as $route) {
            $routeData[] = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => implode(',', $route->gatherMiddleware()),
            ];
        }

        $filename = 'routes-export-'.date('Y-m-d-H-i-s').'.json';
        $filepath = storage_path('app/'.$filename);

        File::put($filepath, json_encode($routeData, JSON_PRETTY_PRINT));

        $this->info("Route information exported to: {$filepath}");

        return 0;
    }

    /**
     * Show optimization status
     */
    private function showOptimizationStatus(): int
    {
        $this->info('Route Optimization Status');
        $this->line('========================');

        $routes = Route::getRoutes();
        $routeCount = $routes->count();

        $this->line("Total Routes: {$routeCount}");
        $this->line('Route Caching: '.(config('route-optimization.cache_routes') ? 'Enabled' : 'Disabled'));
        $this->line('Rate Limiting: '.(config('route-optimization.rate_limiting.api.enabled') ? 'Enabled' : 'Disabled'));
        $this->line('Performance Monitoring: '.(config('route-optimization.monitoring.enabled') ? 'Enabled' : 'Disabled'));

        $this->line("\nAvailable Commands:");
        $this->line('  php artisan routes:optimize --cache    - Cache routes for better performance');
        $this->line('  php artisan routes:optimize --clear    - Clear route cache');
        $this->line('  php artisan routes:optimize --analyze  - Analyze route structure');
        $this->line('  php artisan routes:optimize --export   - Export route information');

        return 0;
    }

    /**
     * Count routes by prefix
     */
    private function countRoutesByPrefix(string $prefix): int
    {
        $routes = Route::getRoutes();
        $count = 0;

        foreach ($routes as $route) {
            if (str_starts_with($route->uri(), $prefix)) {
                $count++;
            }
        }

        return $count;
    }
}
