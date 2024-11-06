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
        return $this->repository->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
