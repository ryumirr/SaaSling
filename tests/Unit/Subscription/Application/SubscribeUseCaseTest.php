<?php

namespace Tests\Unit\Subscription\Application;

use App\Subscription\Application\UseCases\Subscribe\SubscribeInput;
use App\Subscription\Application\UseCases\Subscribe\SubscribeUseCase;
use App\Subscription\Domain\ValueObjects\SubscriptionStatus;
use Tests\Stubs\FakePaymentGateway;
use Tests\Stubs\InMemorySubscriptionRepository;
use Tests\TestCase;

class SubscribeUseCaseTest extends TestCase
{
    private InMemorySubscriptionRepository $repository;
    private FakePaymentGateway $gateway;
    private SubscribeUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        // DB도 Stripe도 없이 순수하게 테스트
        $this->repository = new InMemorySubscriptionRepository();
        $this->gateway    = new FakePaymentGateway();
        $this->useCase    = new SubscribeUseCase($this->repository, $this->gateway);
    }

    public function test_creates_subscription_with_active_status(): void
    {
        $input  = new SubscribeInput(userId: 'user-1', planId: 'premium-monthly');
        $output = $this->useCase->execute($input);

        $this->assertEquals(SubscriptionStatus::ACTIVE->value, $output->status);
    }

    public function test_returns_subscription_id(): void
    {
        $input  = new SubscribeInput(userId: 'user-1', planId: 'premium-monthly');
        $output = $this->useCase->execute($input);

        $this->assertNotEmpty($output->subscriptionId);
    }

    public function test_saves_subscription_to_repository(): void
    {
        $input = new SubscribeInput(userId: 'user-1', planId: 'premium-monthly');
        $this->useCase->execute($input);

        $saved = $this->repository->findByUserId('user-1');
        $this->assertNotNull($saved);
    }

    public function test_calls_payment_gateway(): void
    {
        $input = new SubscribeInput(userId: 'user-1', planId: 'premium-monthly');
        $this->useCase->execute($input);

        $this->assertTrue($this->gateway->createCalled);
    }

    public function test_end_date_is_one_month_from_now_for_monthly_plan(): void
    {
        $input  = new SubscribeInput(userId: 'user-1', planId: 'premium-monthly');
        $output = $this->useCase->execute($input);

        $expectedEndDate = (new \DateTimeImmutable())->modify('+1 month')->format('Y-m-d');

        $this->assertEquals($expectedEndDate, $output->endDate);
    }
}
