<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
                'status' => $this->status->value,
                'total_amount' => $this->total_price,
                'payment_url' => $this->payment_url,
                'expires_at' => $this->expires_at,
                'created_at' => $this->created_at,
                'payment_method' => $this->paymentMethod->name,
                'items' => $this->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,
                        'price_at_purchase' => $product->pivot->price_at_purchase,
                        'subtotal' => round($product->pivot->quantity * $product->pivot->price_at_purchase, 2),
                    ];
                }),
            ],
        ];
    }
}
