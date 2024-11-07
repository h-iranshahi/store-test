<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function placeOrder(Request $request): JsonResponse
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return ResponseHandler::error('Error in validation', ['error' => $validator->errors()], 422);
        }

        // Start the order for the authenticated user
        $this->orderService->startOrder(Auth::user());

        try {
            // Add products to the order
            foreach ($request->product_ids as $index => $productId) {
                $this->orderService->addItemToOrder($productId, $request->quantity[$index]);
            }

            // Finalize the order and create order items
            $order = $this->orderService->finalizeOrder();


            $response = [
                'id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->pivot->product_id,
                        'quantity' => $item->pivot->quantity,
                        'price' => $item->price,
                    ];
                })->toArray(),
            ];

            // Return successful response
            return ResponseHandler::success('Order placed successfully', $response, 201);

        } catch (\InvalidArgumentException $e) {

            return ResponseHandler::error('Error creating product: ' . $e->getMessage(), null, 400);

        }
    }
}
