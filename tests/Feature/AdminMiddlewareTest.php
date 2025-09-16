<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_routes()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_admin_routes()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_user_with_admin_role_but_not_is_admin_can_access()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // This should work because the isAdmin() method checks both role and is_admin
        $response->assertStatus(200);
    }
}
