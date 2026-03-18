<?php

namespace App\Subscription\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'subscription_id' => $this->subscriptionId,
            'status'          => $this->status,
            'end_date'        => $this->endDate ?? null,
        ];
    }
}
