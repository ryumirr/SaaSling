<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Shared\Infrastructure\Slack\SlackNotificationService;

class SlackNotificationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            SlackNotificationService::class,
            SlackNotificationService::class
        );

        $this->app->alias(SlackNotificationService::class, 'Slack');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
