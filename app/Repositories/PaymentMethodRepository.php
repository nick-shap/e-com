<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use App\Repositories\Contracts\PaymentMethodInterface;

class PaymentMethodRepository implements PaymentMethodInterface
{
    public function getSlug(PaymentMethod $paymentMethod): string
    {
        return $paymentMethod->slug;
    }
}
