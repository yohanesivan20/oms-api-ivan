<?php

namespace App\Enums;

enum ShippingStatus: int
{
    case PENDING = 0;
    case SHIPPED = 1;
    case DELIVERED = 2;
    case FAILED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::FAILED => 'Failed',
        };
    }
}