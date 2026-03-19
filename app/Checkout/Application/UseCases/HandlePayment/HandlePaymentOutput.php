<?php

namespace App\Checkout\Application\UseCases\HandlePayment;

class HandlePaymentOutput
{
    public function __construct(
        public readonly string $orderId,
        public readonly string $status,
    ) {}
}
