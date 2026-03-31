<?php

namespace App\Subscription\Domain\ValueObjects;

class Plan
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $price,
        public readonly string $currency,
        public readonly PlanInterval $interval,
    ) {}

    public function nextBillingDate(\DateTimeImmutable $from): \DateTimeImmutable
    {
        return match ($this->interval) {
            PlanInterval::Monthly => $from->modify('+1 month'),
            PlanInterval::Yearly  => $from->modify('+1 year'),
        };
    }
}
