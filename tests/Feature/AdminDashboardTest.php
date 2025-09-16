<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\Dashboard;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_dashboard_displays_correct_stats()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

        // Create test data
        User::factory(5)->create();
        Vendor::factory(3)->create();

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $component->assertSee('5'); // Total users
        $component->assertSee('3'); // Total vendors
    }

    public function test_dashboard_shows_recent_users()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

        $user = User::factory()->create(['name' => 'Test User']);

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $component->assertSee('Test User');
    }

    public function test_dashboard_shows_recent_vendors()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

        $vendor = Vendor::factory()->create(['name' => 'Test Vendor']);

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $component->assertSee('Test Vendor');
    }

    public function test_admin_can_toggle_user_status()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $user = User::factory()->create(['is_active' => true]);

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $component->call('toggleUserStatus', $user->id);

        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    public function test_admin_can_toggle_vendor_status()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);
        $vendor = Vendor::factory()->create(['is_active' => true]);

        $component = Livewire::actingAs($admin)->test(Dashboard::class);

        $component->call('toggleVendorStatus', $vendor->id);

        $vendor->refresh();
        $this->assertFalse($vendor->is_active);
    }
}
