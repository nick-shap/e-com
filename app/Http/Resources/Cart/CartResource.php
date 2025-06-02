<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'data' => [
                'id' => $this->id,
                'items' => CartItemResource::collection($this->whenLoaded('products')),
                'total' => round($this->calculateTotal(), 2),
            ],
        ];
    }

    private function calculateTotal(): float
    {
        return $this->products->sum(
            fn ($product) => round($product->price * $product->pivot->quantity, 2)
        );
    }
}
