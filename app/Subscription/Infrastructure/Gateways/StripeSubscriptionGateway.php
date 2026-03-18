<?php

namespace App\Subscription\Infrastructure\Gateways;

use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Subscription\Domain\ValueObjects\Plan;

class StripeSubscriptionGateway implements PaymentGatewayInterface
{
    public function createSubscription(string $userId, Plan $plan): string
    {
        // TODO: Stripe SDK 연동
        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        // $subscription = $stripe->subscriptions->create([...]);
        // return $subscription->id;

        return 'stripe_sub_placeholder';
    }

    public function cancelSubscription(string $externalId): void
    {
        // TODO: $stripe->subscriptions->cancel($externalId);
    }

    public function renewSubscription(string $externalId): void
    {
        // TODO: Stripe는 자동 갱신이 기본 — 웹훅으로 처리
    }
}
