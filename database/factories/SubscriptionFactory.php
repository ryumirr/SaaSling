<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'id'         => $this->faker->uuid(),
            'user_id'    => User::factory(),
            'plan_id'    => 'premium-monthly',
            'status'     => 'active',
            'start_date' => now(),
            'end_date'   => now()->addMonth(),
        ];
    }

    public function yearly(): static
    {
        return $this->state([
            'plan_id'  => 'premium-yearly',
            'end_date' => now()->addYear(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => 'cancelled',
        ]);
    }

    public function expired(): static
    {
        return $this->state([
            'status'   => 'active',
            'end_date' => now()->subDay(),
        ]);
    }

    public function expiringSoon(): static
    {
        return $this->state([
            'end_date' => now()->addDays(3),
        ]);
    }
}
