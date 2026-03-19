<?php

namespace App\Checkout\Providers;

use App\Checkout\Application\Ports\PaymentServiceInterface;
use App\Checkout\Domain\Repositories\OrderRepositoryInterface;
use App\Checkout\Infrastructure\Gateways\SpringPaymentGateway;
use App\Checkout\Infrastructure\Repositories\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(PaymentServiceInterface::class, SpringPaymentGateway::class);
    }

    public function boot(): void
    {
        //
    }
}
