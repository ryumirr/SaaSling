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
        app()->singleton(
            'Slack',
            SlackNotificationService::class
        );
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
