<?php

namespace Tests\Unit\Subscription\Domain;

use App\Shared\Exceptions\DomainException;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    // -------------------------------------------------------------------------
    // isActive()
    // -------------------------------------------------------------------------

    public function test_active_subscription_is_active(): void
    {
        $subscription = $this->makeSubscription([
            'status'  => SubscriptionStatus::ACTIVE,
            'endDate' => (new \DateTimeImmutable())->modify('+1 month'),
        ]);

        $this->assertTrue($subscription->isActive());
    }

    public function test_expired_subscription_is_not_active(): void
    {
        $subscription = $this->makeSubscription([
            'status'  => SubscriptionStatus::ACTIVE,
            'endDate' => (new \DateTimeImmutable())->modify('-1 day'), // 이미 만료
        ]);

        $this->assertFalse($subscription->isActive());
    }

    public function test_cancelled_subscription_is_not_active(): void
    {
        $subscription = $this->makeSubscription([
            'status' => SubscriptionStatus::CANCELLED,
        ]);

        $this->assertFalse($subscription->isActive());
    }

    // -------------------------------------------------------------------------
    // cancel()
    // -------------------------------------------------------------------------

    public function test_active_subscription_can_be_cancelled(): void
    {
        $subscription = $this->makeSubscription([
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        $subscription->cancel();

        $this->assertEquals(SubscriptionStatus::CANCELLED, $subscription->getStatus());
    }

    public function test_cancelled_subscription_cannot_be_cancelled_again(): void
    {
        $this->expectException(DomainException::class);

        $subscription = $this->makeSubscription([
            'status' => SubscriptionStatus::CANCELLED,
        ]);

        $subscription->cancel(); // 예외 발생
    }

    // -------------------------------------------------------------------------
    // renew()
    // -------------------------------------------------------------------------

    public function test_renew_extends_end_date_by_one_month(): void
    {
        $endDate      = new \DateTimeImmutable('2026-03-18');
        $subscription = $this->makeSubscription([
            'endDate' => $endDate,
            'plan'    => $this->makePlan(['interval' => 'monthly']),
        ]);

        $subscription->renew();

        $this->assertEquals('2026-04-18', $subscription->getEndDate()->format('Y-m-d'));
    }

    public function test_renew_extends_end_date_by_one_year_for_yearly_plan(): void
    {
        $endDate      = new \DateTimeImmutable('2026-03-18');
        $subscription = $this->makeSubscription([
            'endDate' => $endDate,
            'plan'    => $this->makePlan(['interval' => 'yearly']),
        ]);

        $subscription->renew();

        $this->assertEquals('2027-03-18', $subscription->getEndDate()->format('Y-m-d'));
    }

    public function test_cancelled_subscription_cannot_be_renewed(): void
    {
        $this->expectException(DomainException::class);

        $subscription = $this->makeSubscription([
            'status' => SubscriptionStatus::CANCELLED,
        ]);

        $subscription->renew(); // 예외 발생
    }
}
