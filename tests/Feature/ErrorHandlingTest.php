

test('error pages include error ID and timestamp', function () {
    $response = $this->get('/404');
    
    $response->assertStatus(404);
    $response->assertInertia(fn ($page) => 
        $page->has('errorId') && 
        $page->has('timestamp') &&
        str_starts_with($page->get('errorId'), '404-') &&
        !empty($page->get('timestamp'))
    );
});

test('suspicious requests are blocked by security middleware', function () {
    // Test with a route that exists but has suspicious content
    $response = $this->get('/?param=../../../etc/passwd');
    
    // The security middleware should block this before it reaches the route
    $response->assertStatus(403);
    $response->assertJson(['error' => 'Request blocked for security reasons']);
});

test('XSS attempts are blocked', function () {
    $response = $this->get('/?param=<script>alert("xss")</script>');
    
    $response->assertStatus(403);
    $response->assertJson(['error' => 'Request blocked for security reasons']);
});

test('SQL injection attempts are blocked', function () {
    $response = $this->get('/?param=1\' UNION SELECT * FROM users--');
    
    $response->assertStatus(403);
    $response->assertJson(['error' => 'Request blocked for security reasons']);
});

test('rate limiting is applied to error pages', function () {
    // Make 10 requests to trigger rate limiting
    for ($i = 0; $i < 10; $i++) {
        $this->get('/404');
    }

    // The 11th request should be rate limited
    $response = $this->get('/404');
    $response->assertStatus(429);
});

test('DDoS protection blocks rapid requests', function () {
    // Clear cache
    Cache::flush();
    
    // Make rapid requests to trigger DDoS protection
    for ($i = 0; $i < 60; $i++) {
        $this->get('/');
    }

    $response = $this->get('/');
    $response->assertStatus(429);
    $response->assertJson(['error' => 'Too many requests detected']);
});

test('bot abuse detection works', function () {
    $response = $this->withHeaders([
        'User-Agent' => 'sqlmap/1.0'
    ])->get('/');
    
    $response->assertStatus(403);
    $response->assertJson(['error' => 'Bot access restricted']);
});

test('security headers are added to responses', function () {
    $response = $this->get('/404');
    
    $response->assertStatus(404);
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'DENY');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Content-Security-Policy');
    $response->assertHeader('Strict-Transport-Security');
});

test('server information headers are removed', function () {
    $response = $this->get('/404');
    
    $response->assertStatus(404);
    $response->assertHeaderMissing('Server');
    $response->assertHeaderMissing('X-Powered-By');
});

test('different rate limits for different endpoints', function () {
    // Test login rate limiting (5 attempts per 5 minutes)
    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', ['email' => 'test@example.com', 'password' => 'password']);
    }
    
    $response = $this->post('/login', ['email' => 'test@example.com', 'password' => 'password']);
    $response->assertStatus(429);
    
    // Clear rate limiters
    RateLimiter::clear('security_login_127.0.0.1');
    
    // Test register rate limiting (3 attempts per 10 minutes)
    for ($i = 0; $i < 3; $i++) {
        $this->post('/register', ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password']);
    }
    
    $response = $this->post('/register', ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password']);
    $response->assertStatus(429);
});

test('admin endpoints have stricter rate limiting', function () {
    // Test admin rate limiting (30 requests per minute)
    for ($i = 0; $i < 30; $i++) {
        $this->get('/admin/dashboard');
    }
    
    $response = $this->get('/admin/dashboard');
    $response->assertStatus(429);
});

test('error pages handle JSON requests appropriately', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->get('/nonexistent-page');
    
    $response->assertStatus(404);
    $response->assertJsonStructure([
        'error',
        'error_id',
        'timestamp'
    ]);
    $response->assertJson([
        'error' => 'Resource not found'
    ]);
    
    $data = $response->json();
    expect($data['error_id'])->toStartWith('404-');
    expect($data['timestamp'])->not->toBeEmpty();
});

test('validation errors return proper JSON response', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->post('/login', []);
    
    $response->assertStatus(422);
    $response->assertJsonStructure([
        'error',
        'errors',
        'error_id',
        'timestamp'
    ]);
    $response->assertJson([
        'error' => 'Validation failed'
    ]);
    
    $data = $response->json();
    expect($data['error_id'])->toStartWith('422-');
    expect($data['timestamp'])->not->toBeEmpty();
});

test('method not allowed returns proper response', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->put('/login');
    
    $response->assertStatus(405);
    $response->assertJsonStructure([
        'error',
        'error_id',
        'timestamp'
    ]);
    $response->assertJson([
        'error' => 'Method not allowed'
    ]);
    
    $data = $response->json();
    expect($data['error_id'])->toStartWith('405-');
    expect($data['timestamp'])->not->toBeEmpty();
});

test('unauthorized access returns proper response', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json'
    ])->get('/admin/dashboard');
    
    $response->assertStatus(401);
    $response->assertJsonStructure([
        'error',
        'error_id',
        'timestamp'
    ]);
    $response->assertJson([
        'error' => 'Unauthorized'
    ]);
    
    $data = $response->json();
    expect($data['error_id'])->toStartWith('401-');
    expect($data['timestamp'])->not->toBeEmpty();
});

test('error logging includes comprehensive information', function () {
    \Illuminate\Support\Facades\Log::spy();
    
    $this->get('/404');
    
    \Illuminate\Support\Facades\Log::shouldHaveReceived('warning')
        ->with('Client Error', \Closure::class);
});

test('suspicious requests are logged as alerts', function () {
    \Illuminate\Support\Facades\Log::spy();
    
    $this->get('/?param=<script>alert("xss")</script>');
    
    \Illuminate\Support\Facades\Log::shouldHaveReceived('alert')
        ->with('Security Event: SUSPICIOUS_REQUEST_BLOCKED', \Closure::class);
});
