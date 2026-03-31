<?php

namespace App\Subscription\Application\UseCases\Cancel;

class CancelOutput
{
    public function __construct(
        public readonly string $subscriptionId,
        public readonly string $status,
    ) {}
}
