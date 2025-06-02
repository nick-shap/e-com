<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function getAllProducts(int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc'): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts($perPage, $sortBy, $sortDirection);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }
}
