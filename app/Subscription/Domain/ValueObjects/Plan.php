<?php

namespace App\Subscription\Domain\ValueObjects;

class Plan
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $price,
        public readonly string $currency,
        public readonly string $interval, // 'monthly' | 'yearly'
    ) {}

    public function nextBillingDate(\DateTimeImmutable $from): \DateTimeImmutable
    {
        return $this->interval === 'yearly'
            ? $from->modify('+1 year')
            : $from->modify('+1 month');
    }
}
