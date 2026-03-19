<?php

namespace App\Checkout\Infrastructure\Gateways;

use App\Checkout\Application\Ports\PaymentServiceInterface;
use App\Checkout\Domain\Entities\Order;
use Illuminate\Support\Facades\Http;

class SpringPaymentGateway implements PaymentServiceInterface
{
    public function requestPayment(Order $order): string
    {
        $response = Http::post(config('services.payment.url') . '/payments', [
            'order_id'   => $order->id,
            'amount'     => $order->total()->amount,
            'currency'   => $order->total()->currency,
            'webhook_url' => route('checkout.webhook'),
        ]);

        return $response->json('payment_url');
    }
}
