<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_access_user_route()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the authenticated user
        Sanctum::actingAs($user);

        // Send GET request to /user route
        $response = $this->getJson('/api/user');

        // Assert that the response status is 200 and the response has the correct structure
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $user->id,
                         'name' => $user->name,
                         'email' => $user->email,
                         // Any other fields you want to check
                     ]
                 ]);
    }

    /** @test */
    public function an_authenticated_admin_can_access_admin_dashboard_route()
    {
        // Create an admin user (ensure they have a role of admin)
        $admin = User::factory()->create([
            'role' => 'admin', // Assuming 'role' is how you store the user role
        ]);

        // Act as the authenticated admin user
        Sanctum::actingAs($admin);

        // Send GET request to /admin/dashboard route
        $response = $this->getJson('/api/admin/dashboard');

        // Assert that the response status is 200 and the response message is 'Welcome Admin'
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Welcome Admin',
                 ]);
    }

    /** @test */
    public function test_non_admin_cannot_access_admin_dashboard_route()
    {
        // Create a non-admin user
        $user = User::factory()->create([
            'role' => 'user', // Assuming 'role' is how you store the user role
        ]);

        // Act as the non-admin user
        Sanctum::actingAs($user);

        // Send GET request to /admin/dashboard route
        $response = $this->getJson('/api/admin/dashboard');

        // Assert that the response status is 403 (Forbidden) since the user does not have the admin role
        $response->assertStatus(403)
                 ->assertJson([
                     'message' => 'Access Denied',
                 ]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_access_user_route()
    {
        // Send GET request to /user route without authentication
        $response = $this->getJson('/api/user');

        // Assert that the response status is 401 (Unauthorized)
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }
}
