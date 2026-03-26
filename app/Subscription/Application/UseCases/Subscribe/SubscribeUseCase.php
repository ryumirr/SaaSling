<?php

namespace App\Subscription\Application\UseCases\Subscribe;

use App\Subscription\Domain\Entities\Subscription;
use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use App\Subscription\Domain\ValueObjects\Plan;
use App\Subscription\Domain\ValueObjects\PlanInterval;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Shared\Contracts\UuidGeneratorInterface;

class SubscribeUseCase
{
    public function __construct(
        private SubscriptionRepositoryInterface $repository,
        private PaymentGatewayInterface $paymentGateway,
        private UuidGeneratorInterface $uuidGenerator,
    ) {}

    public function execute(SubscribeInput $input): SubscribeOutput
    {
        // TODO: planId로 Plan 조회 (PlanRepository 추가 시 교체)
        $plan = new Plan(
            id: $input->planId,
            name: 'Premium',
            price: 9900,
            currency: 'KRW',
            interval: PlanInterval::Monthly,
        );

        $this->paymentGateway->createSubscription($input->userId, $plan);

        $now          = new \DateTimeImmutable();
        $subscription = new Subscription(
            id: $this->uuidGenerator->generate(),
            userId: $input->userId,
            plan: $plan,
            status: SubscriptionStatus::ACTIVE,
            startDate: $now,
            endDate: $plan->nextBillingDate($now),
        );

        $this->repository->save($subscription);

        return new SubscribeOutput(
            subscriptionId: $subscription->id,
            status: $subscription->getStatus()->value,
            endDate: $subscription->getEndDate()->format('Y-m-d'),
        );
    }
}
