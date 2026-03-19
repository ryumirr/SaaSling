<?php

namespace App\Checkout\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_id'    => $this->orderId,
            'total'       => $this->totalAmount,
            'currency'    => $this->currency,
            'payment_url' => $this->paymentUrl,
        ];
    }
}
