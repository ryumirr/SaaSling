<?php

namespace Tests\Unit\Subscription\Application;

use App\Shared\Exceptions\NotFoundException;
use App\Subscription\Application\UseCases\Cancel\CancelInput;
use App\Subscription\Application\UseCases\Cancel\CancelUseCase;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use Tests\Stubs\FakePaymentGateway;
use Tests\Stubs\InMemorySubscriptionRepository;
use Tests\TestCase;

class CancelUseCaseTest extends TestCase
{
    private InMemorySubscriptionRepository $repository;
    private FakePaymentGateway $gateway;
    private CancelUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemorySubscriptionRepository();
        $this->gateway    = new FakePaymentGateway();
        $this->useCase    = new CancelUseCase($this->repository, $this->gateway);
    }

    public function test_cancels_active_subscription(): void
    {
        $subscription = $this->makeSubscription(['id' => 'sub-1']);
        $this->repository->save($subscription);

        $output = $this->useCase->execute(new CancelInput(subscriptionId: 'sub-1'));

        $this->assertEquals(SubscriptionStatus::CANCELLED->value, $output->status);
    }

    public function test_throws_exception_when_subscription_not_found(): void
    {
        $this->expectException(NotFoundException::class);

        $this->useCase->execute(new CancelInput(subscriptionId: 'non-existent'));
    }

    public function test_persists_cancelled_status(): void
    {
        $subscription = $this->makeSubscription(['id' => 'sub-1']);
        $this->repository->save($subscription);

        $this->useCase->execute(new CancelInput(subscriptionId: 'sub-1'));

        $saved = $this->repository->findById('sub-1');
        $this->assertEquals(SubscriptionStatus::CANCELLED, $saved->getStatus());
    }
}
