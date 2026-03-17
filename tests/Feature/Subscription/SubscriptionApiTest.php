<?php

namespace Tests\Feature\Subscription;

use App\Subscription\Application\UseCases\Subscribe\SubscribeUseCase;
use App\Subscription\Application\UseCases\Cancel\CancelUseCase;
use Tests\Stubs\FakePaymentGateway;
use Tests\Stubs\InMemorySubscriptionRepository;
use Tests\TestCase;

/**
 * Feature 테스트 — 실제 HTTP 요청을 통해 API 엔드포인트를 테스트.
 * UseCase는 Fake 구현체로 교체해서 Stripe/DB 의존성을 제거.
 */
class SubscriptionApiTest extends TestCase
{
    private InMemorySubscriptionRepository $repository;
    private FakePaymentGateway $gateway;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemorySubscriptionRepository();
        $this->gateway    = new FakePaymentGateway();

        // 컨테이너에 Fake 구현체 바인딩
        $this->app->instance(SubscribeUseCase::class, new SubscribeUseCase($this->repository, $this->gateway));
        $this->app->instance(CancelUseCase::class, new CancelUseCase($this->repository, $this->gateway));
    }

    public function test_authenticated_user_can_subscribe(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->postJson('/api/subscriptions', [
            'plan_id' => 'premium-monthly',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'subscription_id',
                     'status',
                     'end_date',
                 ]);
    }

    public function test_unauthenticated_user_cannot_subscribe(): void
    {
        $response = $this->postJson('/api/subscriptions', [
            'plan_id' => 'premium-monthly',
        ]);

        $response->assertStatus(401);
    }

    public function test_subscribe_requires_plan_id(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->postJson('/api/subscriptions', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['plan_id']);
    }

    public function test_subscribe_rejects_invalid_plan_id(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->postJson('/api/subscriptions', [
            'plan_id' => 'invalid-plan',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['plan_id']);
    }

    public function test_authenticated_user_can_cancel_subscription(): void
    {
        $user         = $this->createUser();
        $subscription = $this->makeSubscription(['id' => 'sub-1', 'userId' => $user->id]);
        $this->repository->save($subscription);

        $response = $this->actingAs($user)->deleteJson('/api/subscriptions/sub-1');

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'cancelled']);
    }
}
