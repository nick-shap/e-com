<?php

namespace App\Repositories\Contracts;

use App\Models\PaymentMethod;

interface PaymentMethodInterface
{
    public function getSlug(PaymentMethod $paymentMethod): string;
}
