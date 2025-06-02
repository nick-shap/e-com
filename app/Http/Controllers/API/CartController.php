<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartItemRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function show(): CartResource
    {
        return new CartResource($this->cartService->getCart(User::query()->find(Auth::id())));
    }

    public function add(CartItemRequest $request): JsonResponse
    {
        try {
            $request = $request->validated();

            $product = Product::query()->findOrFail($request['product_id']);

            $this->cartService->addProduct(
                User::query()->find(Auth::id()),
                $product,
                $request['quantity']
            );

            return new JsonResponse([
                'success' => true,
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден',
            ], Response::HTTP_NOT_FOUND);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remove(CartItemRequest $request): JsonResponse
    {
        try {
            $request = $request->validated();

            $product = Product::query()->findOrFail($request['product_id']);

            $this->cartService->removeProduct(
                User::query()->find(Auth::id()),
                $product,
                $request['quantity'] ?? null
            );

            return new JsonResponse([
                'success' => true,
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден',
            ], Response::HTTP_NOT_FOUND);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
