<?php

namespace App\Checkout\Application\UseCases\CreateOrder;

class CreateOrderOutput
{
    public function __construct(
        public readonly string $orderId,
        public readonly int $totalAmount,
        public readonly string $currency,
        public readonly string $paymentUrl, // Spring Boot가 반환한 결제 URL
    ) {}
}
