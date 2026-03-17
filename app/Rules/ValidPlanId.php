<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPlanId implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // TODO: Plan 모델 추가 시 실제 DB 조회로 교체
        $validPlans = ['premium-monthly', 'premium-yearly'];

        if (!in_array($value, $validPlans, strict: true)) {
            $fail('유효하지 않은 플랜입니다.');
        }
    }
}
