<?php
namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_product()
    {
        // Create an admin user and log in
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
                         ->postJson('/api/admin/products', [
                             'name' => 'Test Product',
                             'description' => 'Test description',
                             'price' => 100000,
                             'quantity' => 10
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'description',
                         'price',
                         'quantity',
                     ]
                 ]);
    }

    /** @test */
    public function a_user_can_update_product()
    {
        // Create an admin user and a product
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
                         ->putJson("/api/admin/products/{$product->id}", [
                             'name' => 'Updated Product',
                             'description' => 'Updated description',
                             'price' => 199.99,
                             'quantity' => 5
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Product updated successfully',
                 ]);
    }

    /** @test */
    public function a_user_can_delete_product()
    {
        // Create an admin user and a product
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
                         ->deleteJson("/api/admin/products/{$product->id}");

        $response->assertStatus(204);
    }
}
