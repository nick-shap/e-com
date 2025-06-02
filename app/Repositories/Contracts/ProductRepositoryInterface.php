<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getAllProducts(int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc'): LengthAwarePaginator;

    public function getProductById(int $id): ?Product;
}
