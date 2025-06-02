<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexProductRequest;
use App\Http\Resources\Product\ProductCollectionResource;
use App\Http\Resources\Product\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(IndexProductRequest $request): ProductCollectionResource
    {
        $request = $request->validated();

        $products = $this->productService->getAllProducts(
            $request['per_page'] ?? 20,
            $request['sort_by'] ?? 'created_at',
            $request['sort_order'] ?? 'desc',
        );

        return new ProductCollectionResource($products);
    }

    public function show(int $id): ProductResource|JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден',
            ], Response::HTTP_NOT_FOUND);
        }

        return new ProductResource($product);
    }
}
