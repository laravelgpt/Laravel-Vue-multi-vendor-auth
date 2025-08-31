<?php

use App\Http\Controllers\ErrorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SecurityMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
        ]);

        $middleware->web(append: [
            SecurityMiddleware::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle 403 Forbidden errors
        $exceptions->renderable(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access forbidden',
                    'error_id' => '403-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 403);
            }

            return app(ErrorController::class)->forbidden($request);
        });

        // Handle 404 Not Found errors
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found',
                    'error_id' => '404-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 404);
            }

            return app(ErrorController::class)->notFound($request);
        });

        // Handle 405 Method Not Allowed errors
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Method not allowed',
                    'error_id' => '405-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 405);
            }

            return app(ErrorController::class)->methodNotAllowed($request);
        });

        // Handle 429 Too Many Requests errors
        $exceptions->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Too many requests',
                    'error_id' => '429-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 429);
            }

            return app(ErrorController::class)->tooManyRequests($request);
        });

        // Handle 401 Unauthorized errors
        $exceptions->renderable(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'error_id' => '401-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 401);
            }

            return app(ErrorController::class)->unauthorized($request);
        });

        // Handle validation errors (422)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $e->errors(),
                    'error_id' => '422-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        });

        // Handle general HTTP exceptions
        $exceptions->renderable(function (HttpException $e, Request $request) {
            $statusCode = $e->getStatusCode();

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage() ?: 'HTTP error occurred',
                    'error_id' => $statusCode.'-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], $statusCode);
            }

            return app(ErrorController::class)->generic($request, $statusCode, $e->getMessage());
        });

        // Handle all other exceptions (500 Internal Server Error)
        $exceptions->renderable(function (\Throwable $e, Request $request) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Unhandled exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
                    'error_id' => '500-'.now()->format('YmdHis').'-'.\Illuminate\Support\Str::random(8),
                    'timestamp' => now()->toISOString(),
                ], 500);
            }

            return app(ErrorController::class)->internalServerError($request);
        });
    })->create();
