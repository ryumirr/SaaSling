<?php

namespace App\Subscription\Domain\Repositories;

use App\Subscription\Domain\Entities\Subscription;

interface SubscriptionRepositoryInterface
{
    public function findById(string $id): ?Subscription;
    public function findByUserId(string $userId): ?Subscription;
    public function save(Subscription $subscription): void;
}
