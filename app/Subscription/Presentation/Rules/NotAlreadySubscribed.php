<?php

namespace App\Subscription\Presentation\Rules;

use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotAlreadySubscribed implements ValidationRule
{
    public function __construct(
        private readonly string $userId,
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function validate(string $_attribute, mixed $_value, Closure $fail): void
    {
        if ($this->subscriptionRepository->hasActiveForUser($this->userId)) {
            $fail('이미 활성 구독이 존재합니다.');
        }
    }
}
