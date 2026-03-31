<?php

namespace App\Subscription\Providers;

use App\Subscription\Application\Ports\PaymentGatewayInterface;
use App\Subscription\Domain\Repositories\SubscriptionRepositoryInterface;
use App\Subscription\Infrastructure\Gateways\StripeSubscriptionGateway;
use App\Subscription\Infrastructure\Repositories\EloquentSubscriptionRepository;
use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionRepositoryInterface::class, EloquentSubscriptionRepository::class);
        $this->app->bind(PaymentGatewayInterface::class, StripeSubscriptionGateway::class);
    }

    public function boot(): void
    {
        //
    }
}
