<?php

namespace App\Checkout\Infrastructure\Repositories;

use App\Checkout\Domain\Entities\Order;
use App\Checkout\Domain\Entities\OrderItem;
use App\Checkout\Domain\Repositories\OrderRepositoryInterface;
use App\Checkout\Domain\ValueObjects\Money;
use App\Checkout\Domain\ValueObjects\OrderStatus;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(string $id): ?Order
    {
        $record = \App\Models\Order::with('items')->find($id);

        return $record ? $this->toDomain($record) : null;
    }

    public function save(Order $order): void
    {
        \App\Models\Order::updateOrCreate(
            ['id' => $order->id],
            [
                'user_id'    => $order->userId,
                'account_id' => $order->accountId,
                'status'     => $order->getStatus()->value,
                'total'      => $order->total()->amount,
                'currency'   => $order->total()->currency,
            ]
        );
    }

    private function toDomain(\App\Models\Order $record): Order
    {
        $order = new Order(
            id: $record->id,
            userId: $record->user_id,
            accountId: $record->account_id,
        );

        foreach ($record->items as $item) {
            $order->addItem(new OrderItem(
                productId: $item->product_id,
                quantity: $item->quantity,
                price: new Money($item->price, $record->currency),
            ));
        }

        return $order;
    }
}
