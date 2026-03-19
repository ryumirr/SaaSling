<?php

namespace App\Checkout\Application\UseCases\HandlePayment;

use App\Checkout\Domain\Repositories\OrderRepositoryInterface;
use App\Shared\Exceptions\NotFoundException;

class HandlePaymentUseCase
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(HandlePaymentInput $input): HandlePaymentOutput
    {
        $order = $this->orderRepository->findById($input->orderId);

        if (!$order) {
            throw new NotFoundException('Order not found');
        }

        match ($input->status) {
            'paid'   => $order->markAsPaid(),
            'failed' => $order->markAsFailed(),
            default  => throw new \InvalidArgumentException("Unknown payment status: {$input->status}"),
        };

        $this->orderRepository->save($order);

        return new HandlePaymentOutput(
            orderId: $order->id,
            status: $order->getStatus()->value,
        );
    }
}
