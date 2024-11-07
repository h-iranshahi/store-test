<?php
namespace Tests\Feature\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;
    protected $orderRepository;
    protected $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = Mockery::mock(OrderRepository::class);
        $this->productRepository = Mockery::mock(ProductRepository::class);

        $this->orderService = new OrderService($this->orderRepository, $this->productRepository);


        // Enable foreign key constraints in SQLite
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON;');
        }

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    /** @test */
    public function it_fetches_all_orders()
    {
        $user = User::factory()->create();
        
        $order1 = Order::factory()->create(['user_id' => $user->id, 'total_amount' => 0]);
        $order2 = Order::factory()->create(['user_id' => $user->id, 'total_amount' => 0]);

        $this->orderRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(new Collection([$order1, $order2]));  // Wrapping the orders in an Eloquent collection

        $orders = $this->orderService->getAllOrders();

        $this->assertCount(2, $orders);
    }

    /** @test */
    public function it_fetches_an_order_by_id()
    {
        $order = Order::factory()->make(['id' => 1]);

        $this->orderRepository
            ->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($order);

        $fetchedOrder = $this->orderService->getOrderById(1);

        $this->assertInstanceOf(Order::class, $fetchedOrder);
        $this->assertEquals(1, $fetchedOrder->id);
    }

    /** @test */
    public function it_updates_an_order()
    {
        $orderId = 1;
        $updateData = ['total_amount' => 500.00];

        $this->orderRepository
            ->shouldReceive('update')
            ->once()
            ->with($orderId, $updateData)
            ->andReturn(true);

        $result = $this->orderService->updateOrder($orderId, $updateData);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_deletes_an_order()
    {
        $orderId = 1;

        $this->orderRepository
            ->shouldReceive('delete')
            ->once()
            ->with($orderId)
            ->andReturn(true);

        $result = $this->orderService->deleteOrder($orderId);

        $this->assertTrue($result);
    }
}
