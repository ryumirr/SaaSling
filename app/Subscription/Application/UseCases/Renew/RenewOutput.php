<?php

namespace App\Subscription\Application\UseCases\Renew;

class RenewOutput
{
    public function __construct(
        public readonly string $subscriptionId,
        public readonly string $status,
        public readonly string $endDate,
    ) {}
}
