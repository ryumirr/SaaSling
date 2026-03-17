<?php

namespace App\Subscription\Application\UseCases\Renew;

class RenewInput
{
    public function __construct(
        public readonly string $subscriptionId,
    ) {}
}
