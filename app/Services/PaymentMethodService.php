<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Repositories\Contracts\PaymentMethodInterface;
use Illuminate\Support\Facades\Crypt;

readonly class PaymentMethodService
{
    public function __construct(
        private PaymentMethodInterface $paymentMethodRepository,
    ) {}

    public function generatePaymentUrl(int $orderID, PaymentMethod $paymentMethod): string
    {
        $slug = $this->paymentMethodRepository->getSlug($paymentMethod);

        $encryptedOrderID = Crypt::encryptString($orderID);

        return url('/')."/api/payment/pay/$slug/$encryptedOrderID";
    }
}
