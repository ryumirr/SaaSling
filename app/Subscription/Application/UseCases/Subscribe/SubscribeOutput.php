<?php

namespace App\Subscription\Application\UseCases\Subscribe;

class SubscribeOutput
{
    public function __construct(
        public readonly string $subscriptionId,
        public readonly string $status,
        public readonly string $endDate,
    ) {}
}
