<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TestRunService;
class TestRunServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('TestRunService', function () {
            return new TestRunService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
