<?php

namespace App\Checkout\Domain\ValueObjects;

use App\Shared\Exceptions\DomainException;

class Money
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency, // 'KRW', 'USD'
    ) {}

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new DomainException('Currency mismatch');
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function multiply(int $quantity): self
    {
        return new self($this->amount * $quantity, $this->currency);
    }
}
