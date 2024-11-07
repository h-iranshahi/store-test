<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Helpers\ResponseHandler;

class ProductController extends Controller
{
    protected $productService;

    // Inject ProductService into the controller
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
 
    public function index(Request $request)
    {
        try {
            $products = $this->productService->getProducts($request);

            return ResponseHandler::success('Products fetched successfully', [
                'items' => $products['data'],
                'pagination' => $products['pagination']
            ]);

        } catch (\Exception $e) {
            return ResponseHandler::error('Error finding products: ' . $e->getMessage(), null, 500);
        }

    }
}
