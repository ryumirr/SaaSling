<?php

namespace App\Subscription\Application\UseCases\Renew;

use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Shared\Exceptions\NotFoundException;

class RenewUseCase
{
    public function __construct(
        private SubscriptionRepositoryInterface $repository,
        private PaymentGatewayInterface $paymentGateway,
    ) {}

    public function execute(RenewInput $input): RenewOutput
    {
        $subscription = $this->repository->findById($input->subscriptionId);

        if (!$subscription) {
            throw new NotFoundException('Subscription not found');
        }

        $subscription->renew();
        $this->repository->save($subscription);

        return new RenewOutput(
            subscriptionId: $subscription->id,
            status: $subscription->getStatus()->value,
            endDate: $subscription->getEndDate()->format('Y-m-d'),
        );
    }
}
