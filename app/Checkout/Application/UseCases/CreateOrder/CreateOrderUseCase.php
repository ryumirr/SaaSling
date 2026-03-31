<?php

namespace App\Checkout\Application\UseCases\CreateOrder;

use App\Checkout\Application\Ports\PaymentServiceInterface;
use App\Checkout\Domain\Entities\Order;
use App\Checkout\Domain\Entities\OrderItem;
use App\Checkout\Domain\Repositories\OrderRepositoryInterface;
use App\Checkout\Domain\ValueObjects\Money;
use App\Shared\Contracts\UuidGeneratorInterface;

class CreateOrderUseCase
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly PaymentServiceInterface $paymentService,
        private readonly UuidGeneratorInterface $uuidGenerator,
    ) {}

    public function execute(CreateOrderInput $input): CreateOrderOutput
    {
        $order = new Order(
            id: $this->uuidGenerator->generate(),
            userId: $input->userId,
            accountId: $input->accountId,
        );

        foreach ($input->items as $item) {
            $order->addItem(new OrderItem(
                productId: $item['product_id'],
                quantity: $item['quantity'],
                price: new Money($item['price'], 'KRW'),
            ));
        }

        $this->orderRepository->save($order);

        // Spring Boot 결제 서비스에 결제 요청
        $paymentUrl = $this->paymentService->requestPayment($order);

        return new CreateOrderOutput(
            orderId: $order->id,
            totalAmount: $order->total()->amount,
            currency: $order->total()->currency,
            paymentUrl: $paymentUrl,
        );
    }
}
