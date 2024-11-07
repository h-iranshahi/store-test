<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_order_and_total_amount_is_correct()
    {
        // Create a user and products
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 50000]);
        $product2 = Product::factory()->create(['price' => 15000]);

        $this->actingAs($user);

        // Define quantities for each product
        $quantity1 = 2;
        $quantity2 = 3;

        // Calculate expected total amount
        $expectedTotalAmount = ($product1->price * $quantity1) + ($product2->price * $quantity2);

        // Place order
        $response = $this->postJson('/api/order', [
            'product_ids' => [$product1->id, $product2->id],
            'quantity' => [$quantity1, $quantity2],
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'status',
                'total_amount',
                'items' => [
                    '*' => [
                        'id',
                        'product_id',
                        'quantity',
                        'price',
                    ]
                ]
            ]
        ]);

        // Verify the total amount in the response
        $this->assertEquals($expectedTotalAmount, $response->json('data.total_amount'));

        // Check database for correct total_amount
        $this->assertDatabaseHas('orders', [
            'id' => $response->json('data.id'),
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => $expectedTotalAmount,
        ]);
    }
}
