<?php

namespace Tests\Stubs;

use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Subscription\Domain\ValueObjects\Plan;

/**
 * 테스트 전용 가짜 결제 게이트웨이.
 * Stripe 없이 UseCase를 테스트할 수 있게 해준다.
 */
class FakePaymentGateway implements PaymentGatewayInterface
{
    public bool $createCalled = false;
    public bool $cancelCalled = false;
    public bool $renewCalled  = false;

    public function createSubscription(string $userId, Plan $plan): string
    {
        $this->createCalled = true;

        return 'fake_sub_' . $userId;
    }

    public function cancelSubscription(string $externalId): void
    {
        $this->cancelCalled = true;
    }

    public function renewSubscription(string $externalId): void
    {
        $this->renewCalled = true;
    }
}
