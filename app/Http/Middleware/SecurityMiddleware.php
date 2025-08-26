<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for suspicious patterns
        if ($this->isSuspiciousRequest($request)) {
            $this->logSecurityEvent($request, 'SUSPICIOUS_REQUEST_BLOCKED');
            return $this->createSecurityResponse($request, 403, 'Request blocked for security reasons');
        }

        // Check for DDoS patterns
        if ($this->isDDoSRequest($request)) {
            $this->logSecurityEvent($request, 'DDOS_ATTEMPT_DETECTED');
            return $this->createSecurityResponse($request, 429, 'Too many requests detected');
        }

        // Apply comprehensive rate limiting
        if (!$this->applyRateLimiting($request)) {
            return $this->createSecurityResponse($request, 429, 'Rate limit exceeded');
        }

        // Check for bot/crawler abuse
        if ($this->isBotAbuse($request)) {
            $this->logSecurityEvent($request, 'BOT_ABUSE_DETECTED');
            return $this->createSecurityResponse($request, 403, 'Bot access restricted');
        }

        // Add security headers
        $response = $next($request);
        $this->addSecurityHeaders($response);

        return $response;
    }

    /**
     * Check if request contains suspicious patterns
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        $suspiciousPatterns = [
            // Directory traversal
            '/\.\./',
            
            // XSS attempts
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/onclick=/i',
            
            // SQL injection
            '/union\s+select/i',
            '/select\s+.*\s+from/i',
            '/insert\s+into/i',
            '/update\s+.*\s+set/i',
            '/delete\s+from/i',
            '/drop\s+table/i',
            '/create\s+table/i',
            '/alter\s+table/i',
            
            // Code injection
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec/i',
            '/passthru/i',
            '/popen/i',
            '/proc_open/i',
            
            // File inclusion
            '/include\s*\(/i',
            '/require\s*\(/i',
            '/include_once/i',
            '/require_once/i',
            '/file_get_contents/i',
            '/file_put_contents/i',
            '/fopen/i',
            
            // Command injection
            '/cmd/i',
            '/command/i',
            '/ping\s+.*\s+&/i',
            '/nslookup/i',
            '/whois/i',
            
            // PHP code execution
            '/<\?php/i',
            '/<\?=/i',
            '/<\?/i',
            
            // Base64 and encoding attacks
            '/base64_decode/i',
            '/base64_encode/i',
            '/urlencode/i',
            '/urldecode/i',
            
            // LDAP injection
            '/\*\)/',
            '/\(\|/',
            '/\|\|/',
            
            // XML injection
            '/<!\[CDATA\[/i',
            '/<!DOCTYPE/i',
            '/<!ENTITY/i',
        ];

        $url = $request->fullUrl();
        $userAgent = $request->userAgent() ?? '';
        $referer = $request->header('referer') ?? '';
        $content = $request->getContent();

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url) || 
                preg_match($pattern, $userAgent) || 
                preg_match($pattern, $referer) ||
                preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for DDoS patterns
     */
    private function isDDoSRequest(Request $request): bool
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? '';
        
        // Check for rapid-fire requests
        $rapidKey = "ddos_rapid_{$ip}";
        $rapidCount = Cache::get($rapidKey, 0);
        
        if ($rapidCount > 50) { // More than 50 requests in 1 second
            return true;
        }
        
        Cache::put($rapidKey, $rapidCount + 1, 1); // 1 second TTL
        
        // Check for suspicious user agents
        $suspiciousUserAgents = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python/i',
            '/java/i',
            '/perl/i',
            '/ruby/i',
            '/go-http-client/i',
            '/http-client/i',
        ];
        
        $botCount = 0;
        foreach ($suspiciousUserAgents as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $botCount++;
            }
        }
        
        // If multiple bot patterns detected, likely automated attack
        if ($botCount > 2) {
            $botKey = "ddos_bot_{$ip}";
            $botRequests = Cache::get($botKey, 0);
            
            if ($botRequests > 20) { // More than 20 bot requests per minute
                return true;
            }
            
            Cache::put($botKey, $botRequests + 1, 60); // 1 minute TTL
        }
        
        return false;
    }

    /**
     * Check for bot/crawler abuse
     */
    private function isBotAbuse(Request $request): bool
    {
        $userAgent = $request->userAgent() ?? '';
        $ip = $request->ip();
        
        // Known malicious bot patterns
        $maliciousBots = [
            '/sqlmap/i',
            '/nikto/i',
            '/nmap/i',
            '/dirb/i',
            '/gobuster/i',
            '/wfuzz/i',
            '/burp/i',
            '/zap/i',
            '/acunetix/i',
            '/nessus/i',
            '/openvas/i',
            '/metasploit/i',
            '/beef/i',
            '/sqlsus/i',
            '/havij/i',
            '/pangolin/i',
        ];
        
        foreach ($maliciousBots as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        // Check for excessive requests from same IP with bot-like behavior
        $botKey = "bot_abuse_{$ip}";
        $botRequests = Cache::get($botKey, 0);
        
        if ($botRequests > 100) { // More than 100 requests per hour
            return true;
        }
        
        Cache::put($botKey, $botRequests + 1, 3600); // 1 hour TTL
        
        return false;
    }

    /**
     * Apply comprehensive rate limiting
     */
    private function applyRateLimiting(Request $request): bool
    {
        $ip = $request->ip();
        $path = $request->path();
        $method = $request->method();

        // Different rate limits for different endpoints
        $limits = [
            'login' => ['attempts' => 5, 'decay' => 300], // 5 attempts per 5 minutes
            'register' => ['attempts' => 3, 'decay' => 600], // 3 attempts per 10 minutes
            'password' => ['attempts' => 3, 'decay' => 600], // 3 attempts per 10 minutes
            'otp' => ['attempts' => 5, 'decay' => 300], // 5 OTP attempts per 5 minutes
            'api' => ['attempts' => 60, 'decay' => 60], // 60 requests per minute
            'admin' => ['attempts' => 30, 'decay' => 60], // 30 admin requests per minute
            'default' => ['attempts' => 100, 'decay' => 60], // 100 requests per minute
        ];

        $limitKey = 'default';
        if (str_contains($path, 'login')) {
            $limitKey = 'login';
        } elseif (str_contains($path, 'register')) {
            $limitKey = 'register';
        } elseif (str_contains($path, 'password')) {
            $limitKey = 'password';
        } elseif (str_contains($path, 'otp')) {
            $limitKey = 'otp';
        } elseif (str_contains($path, 'api')) {
            $limitKey = 'api';
        } elseif (str_contains($path, 'admin')) {
            $limitKey = 'admin';
        }

        $rateLimitKey = "security_{$limitKey}_{$ip}";
        $limit = $limits[$limitKey];

        if (RateLimiter::tooManyAttempts($rateLimitKey, $limit['attempts'])) {
            $this->logSecurityEvent($request, 'RATE_LIMIT_EXCEEDED', [
                'limit_key' => $limitKey,
                'attempts' => $limit['attempts'],
                'decay' => $limit['decay'],
            ]);
            return false;
        }

        RateLimiter::hit($rateLimitKey, $limit['decay']);
        return true;
    }

    /**
     * Add comprehensive security headers
     */
    private function addSecurityHeaders(Response $response): void
    {
        // Basic security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions Policy
        $permissionsPolicy = 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), fullscreen=(), picture-in-picture=(), sync-xhr=(), midi=(), clipboard-read=(), clipboard-write=(), web-share=(), cross-origin-isolated=(), display-capture=(), document-domain=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), focus-without-user-activation=(), hid=(), idle-detection=(), interest-cohort=(), keyboard-map=(), navigation-override=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), screen-wake-lock=(), serial=(), speaker-selection=(), sync-script=(), trust-token-redemption=(), usb=(), web-share=(), xr-spatial-tracking=()';
        $response->headers->set('Permissions-Policy', $permissionsPolicy);
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
               "connect-src 'self' https: wss:; " .
               "media-src 'self' https:; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self'; " .
               "frame-ancestors 'none'; " .
               "upgrade-insecure-requests;";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Additional security headers
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        
        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
    }

    /**
     * Create security response
     */
    private function createSecurityResponse(Request $request, int $status, string $message): Response
    {
        $response = response()->json([
            'error' => $message,
            'error_id' => $this->generateErrorId($status),
            'timestamp' => now()->toISOString(),
        ], $status);

        $this->addSecurityHeaders($response);
        
        return $response;
    }

    /**
     * Log security events
     */
    private function logSecurityEvent(Request $request, string $event, array $additionalData = []): void
    {
        $logData = array_merge([
            'event' => $event,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'user_id' => $request->user()?->id,
            'timestamp' => now()->toISOString(),
            'headers' => $request->headers->all(),
        ], $additionalData);

        Log::alert('Security Event: ' . $event, $logData);
    }

    /**
     * Generate unique error ID
     */
    private function generateErrorId(int $status): string
    {
        return sprintf(
            '%s-%s-%s',
            $status,
            now()->format('YmdHis'),
            \Illuminate\Support\Str::random(8)
        );
    }
}
