<?php 
namespace Tests\Feature\Services;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProductService::class);
    }

    public function test_create_product()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'This is one of most popular products',
            'price' => 1000000,
            'quantity' => 10,
        ];

        $product = $this->service->createProduct($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_get_all_products()
    {
        Product::factory()->count(3)->create();
        $products = $this->service->getAllProducts();

        $this->assertCount(3, $products);
    }

    public function test_get_product_by_id()
    {
        $product = Product::factory()->create();
        $foundProduct = $this->service->getProductById($product->id);

        $this->assertEquals($product->id, $foundProduct->id);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create(['name' => 'Old Product']);
        $updated = $this->service->updateProduct($product->id, ['name' => 'Updated Product']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();
        $deleted = $this->service->deleteProduct($product->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
