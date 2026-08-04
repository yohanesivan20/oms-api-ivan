<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case UNPAID = 0;
    case PAID = 1;
    case FAILED = 2;
    case EXPIRED = 3;

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Unpaid',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::EXPIRED => 'Expired',
        };
    }
}