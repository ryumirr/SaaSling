<?php

namespace Tests\Stubs;

use App\Subscription\Domain\Entities\Subscription;
use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;

/**
 * 테스트 전용 In-Memory 구현체.
 * DB 없이 UseCase를 테스트할 수 있게 해준다.
 */
class InMemorySubscriptionRepository implements SubscriptionRepositoryInterface
{
    /** @var array<string, Subscription> */
    private array $store = [];

    public function findById(string $id): ?Subscription
    {
        return $this->store[$id] ?? null;
    }

    public function findByUserId(string $userId): ?Subscription
    {
        foreach ($this->store as $subscription) {
            if ($subscription->userId === $userId) {
                return $subscription;
            }
        }

        return null;
    }

    public function hasActiveForUser(string $userId): bool
    {
        foreach ($this->store as $subscription) {
            if ($subscription->userId === $userId && $subscription->isActive()) {
                return true;
            }
        }

        return false;
    }

    public function save(Subscription $subscription): void
    {
        $this->store[$subscription->id] = $subscription;
    }

    public function all(): array
    {
        return array_values($this->store);
    }
}
