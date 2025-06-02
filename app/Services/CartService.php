<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;

readonly class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function getCart(User $user): Cart
    {
        return $this->cartRepository->getCart($user);
    }

    public function getCartInfo(User $user): array
    {
        $cart = $this->getCart($user);

        $items = $cart->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'subtotal' => $product->price * $product->pivot->quantity,
            ];
        });

        return [
            'cart_id' => $cart->id,
            'items' => $items,
            'total' => $items->sum('subtotal'),
            'items_count' => $items->count(),
        ];
    }

    public function addProduct(User $user, Product $product, int $quantity = 1): void
    {
        $this->cartRepository->addProduct($this->getCart($user), $product, $quantity);
    }

    public function removeProduct(User $user, Product $product, ?int $quantity = null): void
    {
        $this->cartRepository->removeProduct($this->getCart($user), $product, $quantity);
    }
}
