<?php

namespace App\Checkout\Domain\ValueObjects;

enum OrderStatus: string
{
    case PENDING  = 'pending';
    case PAID     = 'paid';
    case FAILED   = 'failed';
    case REFUNDED = 'refunded';

    public function isPending(): bool { return $this === self::PENDING; }
    public function isPaid(): bool { return $this === self::PAID; }
}
