<?php

namespace App\Subscription\Domain\ValueObjects;

enum SubscriptionStatus: string
{
    case PENDING   = 'pending';
    case ACTIVE    = 'active';
    case CANCELLED = 'cancelled';
    case EXPIRED   = 'expired';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }
}
