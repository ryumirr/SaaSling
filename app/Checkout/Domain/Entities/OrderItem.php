<?php

namespace App\Checkout\Domain\Entities;

use App\Checkout\Domain\ValueObjects\Money;

class OrderItem
{
    public function __construct(
        public readonly string $productId,
        public readonly int $quantity,
        private readonly Money $price,
    ) {}

    public function subtotal(): Money
    {
        return $this->price->multiply($this->quantity);
    }

    public function price(): Money
    {
        return $this->price;
    }
}
