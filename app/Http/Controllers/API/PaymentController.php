<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaymentController extends Controller
{
    public function pay(string $paymentMethodSlug, string $orderID): string
    {
        return url('/')."/api/payment/confirm/$orderID";
    }

    public function confirmPayment(string $orderID): JsonResponse
    {
        try {
            $order = Order::query()->find(Crypt::decryptString($orderID));

            if (app(OrderService::class)->markAsPaid($order)) {
                return response()->json([
                    'success' => true,
                    'message' => '',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось подтвердить оплату',
                ], Response::HTTP_OK);
            }

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
