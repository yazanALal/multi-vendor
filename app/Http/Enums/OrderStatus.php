<?php

namespace App\Http\Enums;

enum OrderStatus: string
{
    case pending = 'pending';
    case payment_confirmed = 'payment_confirmed';
    case processed = 'processed';
    case delivered = 'delivered';
}

      