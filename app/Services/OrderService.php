<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;
    
    protected $orderUserId;
    protected $orderItems = [];

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;

    }

    public function startOrder($user)
    {
        $this->orderUserId = $user->id;

        return $this;
    }

    public function addItemToOrder(int $productId, int $quantity)
    {
        $product = $this->productRepository->find($productId);

        if (!$this->orderUserId) {
            throw new \Exception('No order started.');
        }

        if (!$product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        // Add item to order items list
        $this->orderItems[] = [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ];

        return $this;
    }

    public function finalizeOrder()
    {
        if (empty($this->orderItems)) {
            throw new \InvalidArgumentException('No items added to the order.');
        }

        return $this->orderRepository->finalizeOrder($this->orderUserId, $this->orderItems);
    }

    public function getOrderHistory(int $userId, int $page = 1, int $perPage = 10): array
    {
        $orders = $this->orderRepository->getOrdersByUserId($userId, $page, $perPage);

        return [
            'list' => $orders->items(),
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ],
        ];
    }

    public function getAllOrders()
    {
        return $this->orderRepository->all();
    }

    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function updateOrder(int $id, array $data): bool
    {
        return $this->orderRepository->update($id, $data);
    }

    public function deleteOrder(int $id): bool
    {
        return $this->orderRepository->delete($id);
    }
}
