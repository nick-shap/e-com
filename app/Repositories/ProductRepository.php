<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc'): LengthAwarePaginator
    {
        return Product::query()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function getProductById(int $id): ?Product
    {
        return Product::query()->find($id);
    }
}
