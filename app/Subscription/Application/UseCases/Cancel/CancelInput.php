<?php

namespace App\Subscription\Application\UseCases\Cancel;

class CancelInput
{
    public function __construct(
        public readonly string $subscriptionId,
    ) {}
}
