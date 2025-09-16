<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access admin routes', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertStatus(200);
});

test('wholeseller can access wholeseller routes', function () {
    $wholeseller = User::factory()->wholeseller()->create();

    $response = $this->actingAs($wholeseller)->get('/wholeseller/dashboard');

    $response->assertStatus(200);
});

test('customer can access customer routes', function () {
    $customer = User::factory()->customer()->create();

    $response = $this->actingAs($customer)->get('/dashboard');

    $response->assertStatus(200);
});

test('wholeseller cannot access customer dashboard', function () {
    $wholeseller = User::factory()->wholeseller()->create();

    $response = $this->actingAs($wholeseller)->get('/dashboard');

    $response->assertStatus(403);
});

test('customer cannot access wholeseller routes', function () {
    $customer = User::factory()->customer()->create();

    $response = $this->actingAs($customer)->get('/wholeseller/dashboard');

    $response->assertStatus(403);
});

test('guest cannot access protected routes', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');

    $response = $this->get('/wholeseller/dashboard');
    $response->assertRedirect('/login');
});

test('user has role method works', function () {
    $admin = User::factory()->admin()->create();
    $wholeseller = User::factory()->wholeseller()->create();
    $customer = User::factory()->customer()->create();

    expect($admin->hasRole('admin'))->toBeTrue();
    expect($wholeseller->hasRole('wholeseller'))->toBeTrue();
    expect($customer->hasRole('customer'))->toBeTrue();

    expect($admin->hasRole('wholeseller'))->toBeFalse();
    expect($wholeseller->hasRole('customer'))->toBeFalse();
    expect($customer->hasRole('admin'))->toBeFalse();
});

test('user has permission method works', function () {
    $admin = User::factory()->admin()->create();
    $wholeseller = User::factory()->wholeseller()->create();
    $customer = User::factory()->customer()->create();

    // Admin has all permissions
    expect($admin->hasPermission('view_products'))->toBeTrue();
    expect($admin->hasPermission('manage_products'))->toBeTrue();
    expect($admin->hasPermission('view_orders'))->toBeTrue();

    // Wholeseller has wholeseller permissions
    expect($wholeseller->hasPermission('view_products'))->toBeTrue();
    expect($wholeseller->hasPermission('manage_products'))->toBeTrue();
    expect($wholeseller->hasPermission('view_orders'))->toBeTrue();
    expect($wholeseller->hasPermission('admin_only_permission'))->toBeFalse();

    // Customer has customer permissions
    expect($customer->hasPermission('view_products'))->toBeTrue();
    expect($customer->hasPermission('place_orders'))->toBeTrue();
    expect($customer->hasPermission('manage_products'))->toBeFalse();
    expect($customer->hasPermission('view_orders'))->toBeFalse();
});
