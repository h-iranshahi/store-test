<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Helpers\ResponseHandler;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Create a new product
     */
    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'quantity' => 'required|integer',
            ]);

            $product = $this->productService->createProduct($data);

            return ResponseHandler::success('Product created successfully', $product->toArray(), 201);
        } catch (Exception $e) {
            return ResponseHandler::error('Error creating product: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Update an existing product
     */
    public function update($id, Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'quantity' => 'nullable|integer',
            ]);

            $this->productService->updateProduct($id, $data);

            return ResponseHandler::success('Product updated successfully', null, 200);
        } catch (Exception $e) {
            return ResponseHandler::error('Error updating product: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return ResponseHandler::success('Product deleted successfully', null, 204);
        } catch (Exception $e) {
            return ResponseHandler::error('Error deleting product: ' . $e->getMessage(), null, 500);
        }
    }
}
