<?php
namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function all(): Collection
    {
        return Order::all();
    }

    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    public function update(int $id, array $data): bool
    {
        $order = $this->find($id);
        return $order ? $order->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $order = $this->find($id);
        return $order ? $order->delete() : false;
    }

    public function addItemToOrder($order, int $productId, int $quantity, float $price)
    {
        $order->items()->attach($productId, ['quantity' => $quantity]);
    }
 
    public function finalizeOrder($orderUserId, array $orderItems = []): Order
    {
        DB::beginTransaction();
        
        try {

            $order = $this->create([
                    'user_id' => $orderUserId, 
                    'total_amount' => 0]
                );

            foreach ($orderItems as $item) {
                $this->addItemToOrder($order, $item['product_id'], $item['quantity'], $item['price']);
                $order->total_amount += ($item['quantity'] * $item['price']);
            }

            $order->status = 'pending';
            $order->save();

            DB::commit();

            $order->load('items');    

            return $order;

        } catch (\Exception $e) {
            
            DB::rollBack();
            throw new \Exception('Error finalizing order: ' . $e->getMessage());
        }
    }
}
