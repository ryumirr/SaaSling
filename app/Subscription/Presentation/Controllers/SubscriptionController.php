<?php

namespace App\Subscription\Presentation\Controllers;

use App\Http\Controllers\Controller;
use App\Subscription\Application\UseCases\Subscribe\SubscribeInput;
use App\Subscription\Application\UseCases\Subscribe\SubscribeUseCase;
use App\Subscription\Application\UseCases\Cancel\CancelInput;
use App\Subscription\Application\UseCases\Cancel\CancelUseCase;
use App\Subscription\Presentation\Requests\SubscribeRequest;
use App\Subscription\Presentation\Resources\SubscriptionResource;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscribeUseCase $subscribeUseCase,
        private CancelUseCase $cancelUseCase,
    ) {}

    public function store(SubscribeRequest $request): JsonResponse
    {
        $output = $this->subscribeUseCase->execute(new SubscribeInput(
            userId: $request->user()->id,
            planId: $request->validated('plan_id'),
        ));

        return response()->json(new SubscriptionResource($output), 201);
    }

    public function cancel(string $subscriptionId): JsonResponse
    {
        $output = $this->cancelUseCase->execute(new CancelInput(
            subscriptionId: $subscriptionId,
        ));

        return response()->json(new SubscriptionResource($output));
    }
}
