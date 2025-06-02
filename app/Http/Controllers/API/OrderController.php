<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\IndexOrderRequest;
use App\Http\Resources\Order\OrderCollectionResource;
use App\Http\Resources\Order\OrderResource;
use App\Jobs\CancelUnpaidOrder;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function store(CreateOrderRequest $request): JsonResponse|OrderResource
    {
        try {
            $request = $request->validated();

            $user = User::query()->find(Auth::id());

            $paymentMethod = PaymentMethod::query()->findOrFail($request['payment_method_id']);

            $order = $this->orderService->createOrderFromCart(
                $user,
                app(CartService::class)->getCartInfo($user),
                $paymentMethod
            );

            CancelUnpaidOrder::dispatch($order)->delay(now()->addMinutes(2));

            return new OrderResource($order);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(IndexOrderRequest $request): OrderCollectionResource
    {
        $request = $request->validated();

        $orders = $this->orderService->getUserOrders(
            User::query()->find(Auth::id()),
            $request['per_page'] ?? 20,
            $request['sort_by'] ?? 'created_at',
            $request['sort_order'] ?? 'desc',
            $request['status'] ?? null,
        );

        return new OrderCollectionResource($orders);
    }

    public function show(int $id): OrderResource|JsonResponse
    {
        $order = $this->orderService->getOrderDetails($id);

        if (! $order || $order->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Заказ не найден',
            ], Response::HTTP_NOT_FOUND);
        }

        return new OrderResource($order);
    }
}
