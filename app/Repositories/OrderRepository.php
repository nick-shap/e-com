<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function createFromCart(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'payment_method_id' => $data['payment_method_id'],
                'total_price' => $data['total_price'],
                'status' => OrderStatusEnum::PENDING->value,
                'expires_at' => now()->addMinutes(2),
            ]);

            $order->products()->attach($data['items']);

            Cart::query()
                ->where('user_id', $user->id)
                ->delete();

            return $order->load('products');
        });
    }

    public function findById(int $id): ?Order
    {
        return Order::query()
            ->with(['products', 'paymentMethod'])
            ->find($id);
    }

    public function getUserOrders(User $user, int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc', ?string $status = null): LengthAwarePaginator
    {
        $orders = Order::query()->with(['products', 'paymentMethod']);

        if ($status) {
            $orders->where('status', $status);
        }

        return $orders->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }

    public function updateStatus(Order $order, OrderStatusEnum $status): bool
    {
        return $order->update(['status' => $status->value]);
    }

    public function getExpiredPendingOrders(): Collection
    {
        return Order::query()
            ->where('status', OrderStatusEnum::PENDING->value)
            ->where('expires_at', '<', now())
            ->get();
    }
}
