<?php
namespace Tests\Feature\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
    }

    public function test_create_product()
    {
        $data = [
            'name' => 'Product1',
            'description' => 'one of the most popular products',
            'price' => 1000000,
            'quantity' => 10,
        ];

        $product = $this->repository->create($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', ['description' => 'one of the most popular products']);
    }

    public function test_get_all_products()
    {
        Product::factory()->count(3)->create();
        $products = $this->repository->all();

        $this->assertCount(3, $products);
    }

    public function test_find_product_by_id()
    {
        $product = Product::factory()->create();
        $found = $this->repository->find($product->id);

        $this->assertEquals($product->id, $found->id);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create(['name' => 'P1']);
        $updated = $this->repository->update($product->id, ['name' => 'P2']);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('products', ['name' => 'P2']);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();
        $deleted = $this->repository->delete($product->id);

        $this->assertTrue($deleted);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
