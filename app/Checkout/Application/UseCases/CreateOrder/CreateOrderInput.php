<?php

namespace App\Checkout\Application\UseCases\CreateOrder;

class CreateOrderInput
{
    public function __construct(
        public readonly string $userId,
        public readonly string $accountId,
        public readonly array $items, // [['product_id' => '...', 'quantity' => 1, 'price' => 9900], ...]
    ) {}
}
