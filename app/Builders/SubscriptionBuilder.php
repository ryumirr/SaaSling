<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class SubscriptionBuilder extends Builder
{
    public function active(): static
    {
        return $this->where('status', 'active')
                    ->where('end_date', '>', now());
    }

    public function cancelled(): static
    {
        return $this->where('status', 'cancelled');
    }

    public function expired(): static
    {
        return $this->where('status', 'active')
                    ->where('end_date', '<=', now());
    }

    public function expiringSoon(int $days = 7): static
    {
        return $this->active()
                    ->where('end_date', '<', now()->addDays($days));
    }

    public function forUser(string $userId): static
    {
        return $this->where('user_id', $userId);
    }

    public function forPlan(string $planId): static
    {
        return $this->where('plan_id', $planId);
    }
}
