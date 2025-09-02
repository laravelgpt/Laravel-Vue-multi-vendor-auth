<?php

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration form displays all steps', function () {
    $response = $this->get('/register');

    $response->assertInertia(fn ($page) => $page->component('auth/Register'));
});

test('registration form requires all required fields', function () {
    $response = $this->post('/register', []);

    $response->assertSessionHasErrors([
        'name',
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'address_line_1',
        'city',
        'country',
        'terms',
    ]);
});

test('registration form validates email format', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'invalid-email',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'MySecurePassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => true,
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('registration form validates password confirmation', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'DifferentPassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => true,
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('registration form creates user with all fields', function () {
    $userData = [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'MySecurePassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'address_line_1' => '123 Test St',
        'address_line_2' => 'Apt 4B',
        'city' => 'Test City',
        'state' => 'Test State',
        'postal_code' => '12345',
        'country' => 'Test Country',
        'company' => 'Test Company',
        'job_title' => 'Software Developer',
        'department' => 'Engineering',
        'employee_id' => 'EMP001',
        'timezone' => 'America/New_York',
        'language' => 'en',
        'notification_preferences' => 'email',
        'bio' => 'This is a test bio',
        'interests' => 'Programming, Reading, Travel',
        'skills' => 'PHP, Vue.js, Laravel',
        'terms' => true,
    ];

    $response = $this->post('/register', $userData);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = \App\Models\User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);
    $this->assertEquals('Test User', $user->name);
    $this->assertEquals('testuser', $user->username);
    $this->assertEquals('Test', $user->first_name);
    $this->assertEquals('User', $user->last_name);
    $this->assertEquals('1234567890', $user->phone);
    $this->assertEquals('1990-01-01', $user->date_of_birth);
    $this->assertEquals('male', $user->gender);
    $this->assertEquals('123 Test St', $user->address_line_1);
    $this->assertEquals('Apt 4B', $user->address_line_2);
    $this->assertEquals('Test City', $user->city);
    $this->assertEquals('Test State', $user->state);
    $this->assertEquals('12345', $user->postal_code);
    $this->assertEquals('Test Country', $user->country);
    $this->assertEquals('Test Company', $user->company);
    $this->assertEquals('Software Developer', $user->job_title);
    $this->assertEquals('Engineering', $user->department);
    $this->assertEquals('EMP001', $user->employee_id);
    $this->assertEquals('America/New_York', $user->timezone);
    $this->assertEquals('en', $user->language);
    $this->assertEquals('email', $user->notification_preferences);
    $this->assertEquals('This is a test bio', $user->bio);
    $this->assertEquals('Programming, Reading, Travel', $user->interests);
    $this->assertEquals('PHP, Vue.js, Laravel', $user->skills);

    // Verify password is hashed
    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('MySecurePassword123!', $user->password));
});

test('registration form handles optional fields', function () {
    $userData = [
        'name' => 'Test User 2',
        'username' => 'testuser2',
        'email' => 'test2@example.com',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'MySecurePassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => true,
    ];

    $response = $this->post('/register', $userData);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = \App\Models\User::where('email', 'test2@example.com')->first();
    $this->assertNotNull($user);

    // Optional fields should be null or default values
    $this->assertNull($user->date_of_birth);
    $this->assertNull($user->gender);
    $this->assertNull($user->address_line_2);
    $this->assertNull($user->state);
    $this->assertNull($user->postal_code);
    $this->assertNull($user->company);
    $this->assertNull($user->job_title);
    $this->assertNull($user->department);
    $this->assertNull($user->employee_id);
    $this->assertEquals('UTC', $user->timezone);
    $this->assertEquals('en', $user->language);
    $this->assertEquals('email', $user->notification_preferences);
    $this->assertNull($user->bio);
    $this->assertNull($user->interests);
    $this->assertNull($user->skills);
});

test('registration form requires terms acceptance', function () {
    $userData = [
        'name' => 'Test User 3',
        'username' => 'testuser3',
        'email' => 'test3@example.com',
        'password' => 'MySecurePassword123!',
        'password_confirmation' => 'MySecurePassword123!',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => false,
    ];

    $response = $this->post('/register', $userData);

    $response->assertSessionHasErrors(['terms']);
    $this->assertGuest();
});

test('registration form accepts strong password', function () {
    $strongPassword = 'MySecurePassword123!';

    $response = $this->post('/register', [
        'name' => 'Test User 4',
        'username' => 'testuser4',
        'email' => 'test4@example.com',
        'password' => $strongPassword,
        'password_confirmation' => $strongPassword,
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'country' => 'Test Country',
        'terms' => true,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
