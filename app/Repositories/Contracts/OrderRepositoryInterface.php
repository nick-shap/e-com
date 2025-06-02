<?php

namespace App\Repositories\Contracts;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function createFromCart(User $user, array $data): Order;

    public function findById(int $id): ?Order;

    public function getUserOrders(User $user, int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc', ?string $status = null): LengthAwarePaginator;

    public function updateStatus(Order $order, OrderStatusEnum $status): bool;
}
