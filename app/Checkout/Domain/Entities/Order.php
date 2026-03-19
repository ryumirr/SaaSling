<?php

namespace App\Checkout\Domain\Entities;

use App\Checkout\Domain\ValueObjects\Money;
use App\Checkout\Domain\ValueObjects\OrderStatus;
use App\Shared\Exceptions\DomainException;

class Order
{
    private array $items = [];
    private OrderStatus $status = OrderStatus::PENDING;

    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $accountId,
    ) {}

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }

    public function total(): Money
    {
        return array_reduce(
            $this->items,
            fn(Money $carry, OrderItem $item) => $carry->add($item->subtotal()),
            new Money(0, 'KRW')
        );
    }

    public function markAsPaid(): void
    {
        if (!$this->status->isPending()) {
            throw new DomainException('Order is not in pending state');
        }

        $this->status = OrderStatus::PAID;
    }

    public function markAsFailed(): void
    {
        if (!$this->status->isPending()) {
            throw new DomainException('Order is not in pending state');
        }

        $this->status = OrderStatus::FAILED;
    }

    public function getStatus(): OrderStatus { return $this->status; }
    public function getItems(): array { return $this->items; }
}
