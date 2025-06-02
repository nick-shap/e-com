<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

interface CartRepositoryInterface
{
    public function getCart(User $user): Cart;

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): void;

    public function removeProduct(Cart $cart, Product $product): void;
}
