<?php

namespace App\Enums;

enum TenantPaymentMethod: string
{
    case VENMO = 'venmo';
    case CREDIT_CARD = 'credit_card';
    case STRIPE = 'stripe';

    public static function values(): array
    {
        return array_map(fn($c) => $c->value, self::cases());
    }
}
