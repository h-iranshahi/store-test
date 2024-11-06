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

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;

    }

    public function createOrder($userId, array $productData = []): Order
    {

        try {

            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'total_amount' => 0,  
            ]);
       
            DB::beginTransaction();

            $totalAmount = 0;

            foreach ($productData as $data) {
                $product = $this->productRepository->find($data['product_id']);
                $quantity = $data['quantity'];
                $priceForThisProduct = $product->price * $quantity;

                // Attach the product with quantity to the order
                $order->items()->attach($product->id, ['quantity' => $quantity]);

                // Accumulate total price
                $totalAmount += $priceForThisProduct;
            }

            $order->total_amount = $totalAmount;


            $this->orderRepository->update($order->id, ['total_amount' => $totalAmount]);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;  // Log or handle the exception as needed
        }
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
