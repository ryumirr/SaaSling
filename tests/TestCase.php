<?php

namespace Tests;

use App\Models\Account;
use App\Models\User;
use App\Subscription\Domain\Entities\Subscription;
use App\Subscription\Domain\ValueObjects\Plan;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // 유저 헬퍼
    // -------------------------------------------------------------------------

    protected function createAccount(array $attributes = []): Account
    {
        return Account::factory()->create($attributes);
    }

    protected function createUser(array $attributes = []): User
    {
        $attributes['account_id'] ??= $this->createAccount()->id;

        return User::factory()->create($attributes);
    }

    // -------------------------------------------------------------------------
    // 도메인 객체 헬퍼 (DB 없이 단위 테스트에서 사용)
    // -------------------------------------------------------------------------

    protected function makePlan(array $attributes = []): Plan
    {
        return new Plan(
            id: $attributes['id'] ?? 'premium-monthly',
            name: $attributes['name'] ?? 'Premium Monthly',
            price: $attributes['price'] ?? 9900,
            currency: $attributes['currency'] ?? 'KRW',
            interval: $attributes['interval'] ?? 'monthly',
        );
    }

    protected function makeSubscription(array $attributes = []): Subscription
    {
        $now = new \DateTimeImmutable();

        return new Subscription(
            id: $attributes['id'] ?? 'sub-test-1',
            userId: $attributes['userId'] ?? 'user-test-1',
            plan: $attributes['plan'] ?? $this->makePlan(),
            status: $attributes['status'] ?? SubscriptionStatus::ACTIVE,
            startDate: $attributes['startDate'] ?? $now,
            endDate: $attributes['endDate'] ?? $now->modify('+1 month'),
        );
    }
}
