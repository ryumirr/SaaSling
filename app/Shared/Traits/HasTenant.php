<?php

namespace App\Shared\Traits;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 멀티테넌시 트레이트.
 * 이 트레이트를 사용하는 모델은 항상 현재 인증된 유저의 account_id로 필터링된다.
 *
 * 사용법: 모델에 `use HasTenant;` 추가
 * 조건: 모델 테이블에 `account_id` 컬럼이 있어야 함
 */
trait HasTenant
{
    public static function bootHasTenant(): void
    {
        // 글로벌 스코프: 모든 쿼리에 account_id 자동 필터링
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check()) {
                $query->where(
                    (new static())->getTable() . '.account_id',
                    auth()->user()->account_id
                );
            }
        });

        // 생성 시 account_id 자동 할당
        static::creating(function ($model) {
            if (auth()->check() && empty($model->account_id)) {
                $model->account_id = auth()->user()->account_id;
            }
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * 글로벌 스코프를 무시하고 전체 계정 데이터 조회 (관리자용)
     */
    public static function withoutTenant(): Builder
    {
        return static::withoutGlobalScope('tenant');
    }
}
