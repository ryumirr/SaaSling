<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool used()
 */
class Slack extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Slack';
    }
}
