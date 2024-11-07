<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_products()
    {
        $user = User::factory()->create();

        Product::factory()->create([
            'name' => 'iPhone 13',
            'price' => 60000000,
        ]);
        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'price' => 25000000,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/products');

        $response->assertStatus(200);
                 
        $response->assertJsonStructure([
            'success',
            'statusCode',
            'message',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'quantity',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                ]
            ]
        ]);
                                    
        // Assert that the correct products are returned
        $response->assertJsonFragment(['name' => 'iPhone 13']);
        $response->assertJsonFragment(['name' => 'Samsung Galaxy']);

        // Assert that pagination is correctly calculated
        $response->assertJsonFragment([
            'total' => 2,
            'per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
            'from' => 1,
            'to' => 2,
        ]);

    }

    public function test_user_can_filter_products_by_name()
    {
        $user = User::factory()->create();

        Product::factory()->create([
            'name' => 'iPhone 13',
            'price' => 999,
        ]);
        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'price' => 799,
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/products?name=iPhone');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data.items');
    }
}
