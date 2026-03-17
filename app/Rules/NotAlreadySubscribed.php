<?php

namespace App\Rules;

use App\Models\Subscription;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotAlreadySubscribed implements ValidationRule
{
    public function __construct(private readonly string $userId) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Subscription::query()->forUser($this->userId)->active()->exists();

        if ($exists) {
            $fail('이미 활성 구독이 존재합니다.');
        }
    }
}
