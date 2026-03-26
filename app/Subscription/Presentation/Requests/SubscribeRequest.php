<?php

namespace App\Subscription\Presentation\Requests;

use App\Rules\NotAlreadySubscribed;
use App\Rules\ValidPlanId;
use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function __construct(private readonly SubscriptionRepositoryInterface $subscriptionRepository)
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => [
                'required',
                'string',
                new ValidPlanId(),
                new NotAlreadySubscribed($this->user()->id, $this->subscriptionRepository),
            ],
        ];
    }
}
