<?php

namespace App\Checkout\Presentation\Controllers;

use App\Checkout\Application\UseCases\HandlePayment\HandlePaymentInput;
use App\Checkout\Application\UseCases\HandlePayment\HandlePaymentUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __construct(
        private readonly HandlePaymentUseCase $handlePaymentUseCase,
    ) {}

    public function handle(Request $request): Response
    {
        $this->handlePaymentUseCase->execute(new HandlePaymentInput(
            orderId: $request->input('order_id'),
            status: $request->input('status'),    // 'paid' | 'failed'
        ));

        return response('OK', 200);
    }
}
