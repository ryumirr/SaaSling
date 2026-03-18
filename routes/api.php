<?php

use App\Subscription\Presentation\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'cancel']);
    Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew']);
});
