<?php

namespace App\Subscription\Domain\Entities;

use App\Subscription\Domain\ValueObjects\Plan;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use App\Shared\Exceptions\DomainException;

class Subscription
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        private Plan $plan,
        private SubscriptionStatus $status,
        public readonly \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
    ) {}

    public function cancel(): void
    {
        if (!$this->status->isActive()) {
            throw new DomainException('Subscription is not active');
        }

        $this->status = SubscriptionStatus::CANCELLED;
    }

    public function renew(): void
    {
        if ($this->status->isCancelled()) {
            throw new DomainException('Cannot renew a cancelled subscription');
        }

        $this->endDate = $this->plan->nextBillingDate($this->endDate);
        $this->status  = SubscriptionStatus::ACTIVE;
    }

    public function isActive(): bool
    {
        return $this->status->isActive() && $this->endDate > new \DateTimeImmutable();
    }

    public function getStatus(): SubscriptionStatus { return $this->status; }
    public function getPlan(): Plan { return $this->plan; }
    public function getEndDate(): \DateTimeImmutable { return $this->endDate; }
}
