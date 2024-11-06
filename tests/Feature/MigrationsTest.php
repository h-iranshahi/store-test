<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class MigrationsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_users_table_has_expected_columns()
    {
        // Run the migrations
        $this->artisan('migrate', ['--env' => 'testing'])->run();

        // Check if the 'users' table exists
        $this->assertTrue(Schema::hasTable('users'));

        // Check if the 'users' table has the expected columns
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'name', 'email', 'password', 'remember_token', 'created_at', 'updated_at'
        ]));
    }


    public function test_products_table_has_expected_columns()
    {
        // Run the migrations
        $this->artisan('migrate', ['--env' => 'testing'])->run();

        // Check if the 'products' table exists
        $this->assertTrue(Schema::hasTable('products'));

        // Check if the 'products' table has the expected columns
        $this->assertTrue(Schema::hasColumns('products', [
            'id', 'name', 'description', 'price', 'quantity', 'created_at', 'updated_at'
        ]));
    }


    public function test_orders_table_has_expected_columns()
    {
        // Run the migrations
        $this->artisan('migrate', ['--env' => 'testing'])->run();

        // Check if the 'orders' table exists
        $this->assertTrue(Schema::hasTable('orders'));

        // Check if the 'orders' table has the expected columns
        $this->assertTrue(Schema::hasColumns('orders', [
            'id', 'user_id', 'total_amount', 'status', 'created_at', 'updated_at'
        ]));
    }

    
    public function test_order_product_table_has_expected_columns()
    {
        // Run the migrations
        $this->artisan('migrate', ['--env' => 'testing'])->run();

        // Check if the 'order_product' table exists
        $this->assertTrue(Schema::hasTable('order_product'));

        // Check if the 'order_product' table has the expected columns
        $this->assertTrue(Schema::hasColumns('order_product', [
            'id', 'order_id', 'product_id', 'quantity', 'created_at', 'updated_at'
        ]));
    }

}
