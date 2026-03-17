<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TestService;

class TestProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('TestService', function () {
            return new TestService();
        });
    }

    public function boot(): void
    {
        //
    }
}
