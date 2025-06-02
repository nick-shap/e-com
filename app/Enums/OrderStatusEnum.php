<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'На оплату';
    case PAID = 'Оплачен';
    case CANCELLED = 'Отменён';
}
