<?php

namespace App\Subscription\Application\UseCases\Subscribe;

class SubscribeInput
{
    public function __construct(
        public readonly string $userId,
        public readonly string $planId,
    ) {}
}
