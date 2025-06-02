<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private PaymentMethodService $paymentMethodService,
    ) {}

    public function createOrderFromCart(User $user, array $cartData, PaymentMethod $paymentMethod): Order
    {
        if ($cartData['items']->isEmpty()) {
            throw new \Exception('Пустая корзина');
        }

        $order = $this->orderRepository->createFromCart($user, [
            'payment_method_id' => $paymentMethod->id,
            'total_price' => $cartData['total'],
            'items' => $cartData['items']->mapWithKeys(function ($item) {
                return [
                    $item['id'] => [
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $item['price'],
                    ],
                ];
            })->all(),
        ]);

        $order->update([
            'payment_url' => $this->paymentMethodService->generatePaymentUrl($order->id, $paymentMethod),
        ]);

        return $order;
    }

    public function getOrderDetails(int $orderId): ?Order
    {
        return $this->orderRepository->findById($orderId);
    }

    public function getUserOrders(User $user, int $perPage = 20, string $sortBy = 'created_at', string $sortDirection = 'desc', ?string $status = null): LengthAwarePaginator
    {
        return $this->orderRepository->getUserOrders($user, $perPage, $sortBy, $sortDirection, $status);
    }

    public function markAsPaid(Order $order): bool
    {
        if ($order->status === OrderStatusEnum::CANCELLED) {
            return false;
        }

        return $this->orderRepository->updateStatus($order, OrderStatusEnum::PAID);
    }

    public function markAsCancelled(Order $order): bool
    {
        if ($order->status === OrderStatusEnum::PAID || $order->status === OrderStatusEnum::CANCELLED) {
            return false;
        }

        return $this->orderRepository->updateStatus($order, OrderStatusEnum::CANCELLED);
    }
}
