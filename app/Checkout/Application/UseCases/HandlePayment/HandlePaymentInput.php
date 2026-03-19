<?php

namespace App\Checkout\Application\UseCases\HandlePayment;

class HandlePaymentInput
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $status,  // 'paid' | 'failed'
    ) {}
}
