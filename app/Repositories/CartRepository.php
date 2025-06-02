<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getCart(User $user): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id' => $user->id,
        ])->load('products');
    }

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): void
    {
        $cart->products()->syncWithoutDetaching([
            $product->id => [
                'quantity' => $cart->products()
                    ->where('product_id', $product->id)
                    ->first()
                    ?->pivot
                    ?->quantity + $quantity,
            ],
        ]);
    }

    public function removeProduct(Cart $cart, Product $product, ?int $quantity = null): void
    {
        if (is_null($quantity)) {
            $cart->products()->detach($product->id);

            return;
        }

        $currentQuantity = $cart->products()
            ->where('product_id', $product->id)
            ->first()
            ->pivot
            ->quantity;

        $newQuantity = max(0, $currentQuantity - $quantity);

        if ($newQuantity > 0) {
            $cart->products()->updateExistingPivot($product->id, [
                'quantity' => $newQuantity,
            ]);
        } else {
            $cart->products()->detach($product->id);
        }
    }
}
