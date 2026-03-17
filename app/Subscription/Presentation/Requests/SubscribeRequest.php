<?php

namespace App\Subscription\Presentation\Requests;

use App\Rules\NotAlreadySubscribed;
use App\Rules\ValidPlanId;
use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'string', new ValidPlanId(), new NotAlreadySubscribed($this->user()->id)],
        ];
    }
}
