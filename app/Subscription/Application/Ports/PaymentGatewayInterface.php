<?php

namespace App\Subscription\Application\Ports;

use App\Subscription\Domain\ValueObjects\Plan;

interface PaymentGatewayInterface
{
    public function createSubscription(string $userId, Plan $plan): string; // 외부 구독 ID 반환
    public function cancelSubscription(string $externalId): void;
    public function renewSubscription(string $externalId): void;
}
