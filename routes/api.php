<?php

use App\Checkout\Presentation\Controllers\CheckoutController;
use App\Checkout\Presentation\Controllers\WebhookController;
use App\Checkout\Presentation\Middleware\VerifyPaymentSignature;
use App\Subscription\Presentation\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Subscription
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'cancel']);
    Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew']);

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'store']);
});

// 웹훅 — 서명 검증만 (Spring Boot에서 호출)
Route::post('/webhook/payment', [WebhookController::class, 'handle'])
    ->middleware(VerifyPaymentSignature::class)
    ->name('checkout.webhook');
