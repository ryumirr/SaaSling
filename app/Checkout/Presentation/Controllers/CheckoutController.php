<?php

namespace App\Checkout\Presentation\Controllers;

use App\Checkout\Application\UseCases\CreateOrder\CreateOrderInput;
use App\Checkout\Application\UseCases\CreateOrder\CreateOrderUseCase;
use App\Checkout\Presentation\Requests\CreateOrderRequest;
use App\Checkout\Presentation\Resources\OrderResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CreateOrderUseCase $createOrderUseCase,
    ) {}

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $output = $this->createOrderUseCase->execute(new CreateOrderInput(
            userId: $request->user()->id,
            accountId: $request->user()->account_id,
            items: $request->validated('items'),
        ));

        return response()->json(new OrderResource($output), 201);
    }
}
