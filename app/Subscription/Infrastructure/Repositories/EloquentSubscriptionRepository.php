<?php

namespace App\Subscription\Infrastructure\Repositories;

use App\Subscription\Domain\Entities\Subscription;
use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use App\Subscription\Domain\ValueObjects\Plan;
use App\Subscription\Domain\ValueObjects\PlanInterval;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;

class EloquentSubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function findById(string $id): ?Subscription
    {
        $record = \App\Models\Subscription::find($id);

        return $record ? $this->toDomain($record) : null;
    }

    public function findByUserId(string $userId): ?Subscription
    {
        $record = \App\Models\Subscription::where('user_id', $userId)->latest()->first();

        return $record ? $this->toDomain($record) : null;
    }

    public function hasActiveForUser(string $userId): bool
    {
        return \App\Models\Subscription::forUser($userId)->active()->exists();
    }

    public function save(Subscription $subscription): void
    {
        \App\Models\Subscription::updateOrCreate(
            ['id' => $subscription->id],
            [
                'user_id'    => $subscription->userId,
                'plan_id'    => $subscription->getPlan()->id,
                'status'     => $subscription->getStatus()->value,
                'start_date' => $subscription->startDate->format('Y-m-d H:i:s'),
                'end_date'   => $subscription->getEndDate()->format('Y-m-d H:i:s'),
            ]
        );
    }

    private function toDomain(\App\Models\Subscription $record): Subscription
    {
        $plan = new Plan(
            id: $record->plan_id,
            name: $record->plan->name,
            price: $record->plan->price,
            currency: $record->plan->currency,
            interval: PlanInterval::from($record->plan->interval),
        );

        return new Subscription(
            id: $record->id,
            userId: $record->user_id,
            plan: $plan,
            status: SubscriptionStatus::from($record->status),
            startDate: new \DateTimeImmutable($record->start_date),
            endDate: new \DateTimeImmutable($record->end_date),
        );
    }
}
