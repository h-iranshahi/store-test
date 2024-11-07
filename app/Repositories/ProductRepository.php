<?php
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function all(): Collection
    {
        return Product::all();
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        return $product ? $product->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        return $product ? $product->delete() : false;
    }

    public function getProducts($request)
    {
        $perPage = $request->input('per_page', 10);  

        $query = Product::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        return $query->paginate($perPage);
    }


}
