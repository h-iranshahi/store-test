<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    public function getProducts($request)
    {
        $paginator = $this->repository->getProducts($request);

        // Extract pagination data
        return [
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function getAllProducts()
    {
        return $this->repository->all();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->repository->find($id);
    }

    public function updateProduct(int $id, array $data): bool
    {
        $product = $this->repository->find($id);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        return $this->repository->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->repository->find($id);

        if (!$product) {
            throw new \Exception("Product not found");
        }
        
        return $this->repository->delete($id);
    }
}
