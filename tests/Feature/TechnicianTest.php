<?php

use App\Http\Livewire\Technician\Dashboard;
use App\Models\User;
use Livewire\Livewire;

test('technician can access technician dashboard', function () {
    $technician = User::factory()->technician()->create();

    $response = $this->actingAs($technician)->get('/technician/dashboard');

    $response->assertStatus(200);
});

test('technician can access technician routes', function () {
    $technician = User::factory()->technician()->create();

    $routes = [
        '/technician/dashboard',
        '/technician/repairs',
        '/technician/active-repairs',
        '/technician/history',
        '/technician/parts',
        '/technician/tools',
        '/technician/reports',
    ];

    foreach ($routes as $route) {
        $response = $this->actingAs($technician)->get($route);
        $response->assertStatus(200);
    }
});

test('non-technician cannot access technician routes', function () {
    $customer = User::factory()->customer()->create();

    $response = $this->actingAs($customer)->get('/technician/dashboard');

    $response->assertStatus(403);
});

test('technician dashboard livewire component works', function () {
    $technician = User::factory()->technician()->create();

    Livewire::actingAs($technician)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Technician Dashboard')
        ->assertSee('Manage your repair orders and track your work');
});

test('technician can update status', function () {
    $technician = User::factory()->technician()->create();

    Livewire::actingAs($technician)
        ->test(Dashboard::class)
        ->call('updateStatus', 'unavailable')
        ->assertSee('Status updated successfully!');
});

test('technician has correct permissions', function () {
    $technician = User::factory()->technician()->create();

    expect($technician->isTechnician())->toBeTrue();
    expect($technician->hasRole('technician'))->toBeTrue();
    expect($technician->hasPermission('view_repairs'))->toBeTrue();
    expect($technician->hasPermission('manage_repairs'))->toBeTrue();
    expect($technician->hasPermission('view_repair_orders'))->toBeTrue();
    expect($technician->hasPermission('update_repair_status'))->toBeTrue();
    expect($technician->hasPermission('view_repair_history'))->toBeTrue();
    expect($technician->hasPermission('manage_repair_parts'))->toBeTrue();
    expect($technician->hasPermission('view_repair_reports'))->toBeTrue();
});

test('technician cannot access admin routes', function () {
    $technician = User::factory()->technician()->create();

    $response = $this->actingAs($technician)->get('/admin/dashboard');

    $response->assertStatus(403);
});

test('technician cannot access wholeseller routes', function () {
    $technician = User::factory()->technician()->create();

    $response = $this->actingAs($technician)->get('/wholeseller/dashboard');

    $response->assertStatus(403);
});

test('technician cannot access customer routes', function () {
    $technician = User::factory()->technician()->create();

    $response = $this->actingAs($technician)->get('/dashboard');

    $response->assertStatus(403);
});
