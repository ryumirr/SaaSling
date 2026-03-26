<?php

namespace App\Providers;

use App\Shared\Contracts\UuidGeneratorInterface;
use App\Shared\Infrastructure\RamseyUuidGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UuidGeneratorInterface::class, RamseyUuidGenerator::class);
    }

    public function boot(): void
    {
        //
    }
}
