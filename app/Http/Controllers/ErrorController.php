<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    /**
     * Handle 403 Forbidden errors
     */
    public function forbidden(Request $request): Response
    {
        $this->logError($request, 403, 'Forbidden');
        $this->checkRateLimit($request, 'error_403');
        
        return Inertia::render('errors/403', [
            'errorId' => $this->generateErrorId(403),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(403);
    }

    /**
     * Handle 404 Not Found errors
     */
    public function notFound(Request $request): Response
    {
        $this->logError($request, 404, 'Not Found');
        $this->checkRateLimit($request, 'error_404');
        
        return Inertia::render('errors/404', [
            'errorId' => $this->generateErrorId(404),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(404);
    }

    /**
     * Handle 500 Internal Server errors
     */
    public function internalServerError(Request $request): Response
    {
        $this->logError($request, 500, 'Internal Server Error');
        $this->checkRateLimit($request, 'error_500');
        
        return Inertia::render('errors/500', [
            'errorId' => $this->generateErrorId(500),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(500);
    }

    /**
     * Handle 401 Unauthorized errors
     */
    public function unauthorized(Request $request): Response
    {
        $this->logError($request, 401, 'Unauthorized');
        $this->checkRateLimit($request, 'error_401');
        
        return Inertia::render('errors/401', [
            'errorId' => $this->generateErrorId(401),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(401);
    }

    /**
     * Handle 429 Too Many Requests errors
     */
    public function tooManyRequests(Request $request): Response
    {
        $this->logError($request, 429, 'Too Many Requests');
        
        return Inertia::render('errors/429', [
            'errorId' => $this->generateErrorId(429),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(429);
    }

    /**
     * Handle 503 Maintenance errors
     */
    public function maintenance(Request $request): Response
    {
        $this->logError($request, 503, 'Service Unavailable - Maintenance');
        $this->checkRateLimit($request, 'maintenance');
        
        return Inertia::render('errors/503', [
            'errorId' => $this->generateErrorId(503),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(503);
    }

    /**
     * Handle generic errors
     */
    public function generic(Request $request, int $status = 500, string $message = null): Response
    {
        $this->logError($request, $status, $message ?? 'Generic Error');
        $this->checkRateLimit($request, "error_{$status}");
        
        return Inertia::render('errors/Generic', [
            'status' => $status,
            'message' => $message,
            'errorId' => $this->generateErrorId($status),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode($status);
    }

    /**
     * Handle bad request (400)
     */
    public function badRequest(Request $request): Response
    {
        $this->logError($request, 400, 'Bad Request');
        $this->checkRateLimit($request, 'error_400');
        
        return Inertia::render('errors/Generic', [
            'status' => 400,
            'message' => 'The request could not be understood by the server due to malformed syntax.',
            'errorId' => $this->generateErrorId(400),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(400);
    }

    /**
     * Handle method not allowed (405)
     */
    public function methodNotAllowed(Request $request): Response
    {
        $this->logError($request, 405, 'Method Not Allowed');
        $this->checkRateLimit($request, 'error_405');
        
        return Inertia::render('errors/Generic', [
            'status' => 405,
            'message' => 'The method specified in the request is not allowed for the resource.',
            'errorId' => $this->generateErrorId(405),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(405);
    }

    /**
     * Handle validation errors (422)
     */
    public function unprocessableEntity(Request $request): Response
    {
        $this->logError($request, 422, 'Unprocessable Entity');
        $this->checkRateLimit($request, 'error_422');
        
        return Inertia::render('errors/Generic', [
            'status' => 422,
            'message' => 'The server understands the content type of the request entity, and the syntax of the request entity is correct, but it was unable to process the contained instructions.',
            'errorId' => $this->generateErrorId(422),
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
        ])->toResponse($request)->setStatusCode(422);
    }

    /**
     * Log error details for security monitoring
     */
    private function logError(Request $request, int $status, string $message): void
    {
        $logData = [
            'error_id' => $this->generateErrorId($status),
            'status' => $status,
            'message' => $message,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'user_id' => $request->user()?->id,
            'timestamp' => now()->toISOString(),
            'headers' => $request->headers->all(),
        ];

        // Log with different levels based on status code
        if ($status >= 500) {
            Log::error('Server Error', $logData);
        } elseif ($status >= 400) {
            Log::warning('Client Error', $logData);
        } else {
            Log::info('Informational Error', $logData);
        }

        // Additional security logging for suspicious requests
        if ($this->isSuspiciousRequest($request)) {
            Log::alert('Suspicious Request Detected', $logData);
        }
    }

    /**
     * Check rate limiting for error pages
     */
    private function checkRateLimit(Request $request, string $key): void
    {
        $ip = $request->ip();
        $rateLimitKey = "error_{$key}_{$ip}";
        
        // Allow 10 error page visits per minute per IP
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            Log::warning('Rate limit exceeded for error pages', [
                'ip' => $ip,
                'key' => $key,
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        RateLimiter::hit($rateLimitKey, 60); // 1 minute decay
    }

    /**
     * Generate unique error ID for tracking
     */
    private function generateErrorId(int $status): string
    {
        return sprintf(
            '%s-%s-%s',
            $status,
            now()->format('YmdHis'),
            Str::random(8)
        );
    }

    /**
     * Check if request is suspicious for security monitoring
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        $suspiciousPatterns = [
            '/\.\./', // Directory traversal
            '/<script/i', // XSS attempts
            '/union\s+select/i', // SQL injection
            '/eval\s*\(/i', // Code injection
            '/system\s*\(/i', // Command injection
            '/exec\s*\(/i', // Command injection
            '/shell_exec/i', // Command injection
            '/passthru/i', // Command injection
        ];

        $url = $request->fullUrl();
        $userAgent = $request->userAgent() ?? '';
        $referer = $request->header('referer') ?? '';

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url) || 
                preg_match($pattern, $userAgent) || 
                preg_match($pattern, $referer)) {
                return true;
            }
        }

        // Check for excessive requests from same IP
        $ip = $request->ip();
        $rateLimitKey = "suspicious_{$ip}";
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 50)) {
            return true;
        }

        return false;
    }

    /**
     * Get error statistics for monitoring
     */
    public function getErrorStats(): array
    {
        // This would typically query a database or cache for error statistics
        // For now, return basic structure
        return [
            'total_errors' => 0,
            'errors_by_status' => [],
            'errors_by_hour' => [],
            'suspicious_requests' => 0,
            'rate_limited_ips' => 0,
        ];
    }
}
