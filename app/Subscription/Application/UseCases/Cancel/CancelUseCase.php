<?php

namespace App\Subscription\Application\UseCases\Cancel;

use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Shared\Exceptions\NotFoundException;

class CancelUseCase
{
    public function __construct(
        private SubscriptionRepositoryInterface $repository,
        private PaymentGatewayInterface $paymentGateway,
    ) {}

    public function execute(CancelInput $input): CancelOutput
    {
        $subscription = $this->repository->findById($input->subscriptionId);

        if (!$subscription) {
            throw new NotFoundException('Subscription not found');
        }

        $subscription->cancel();
        $this->repository->save($subscription);

        return new CancelOutput(
            subscriptionId: $subscription->id,
            status: $subscription->getStatus()->value,
        );
    }
}
