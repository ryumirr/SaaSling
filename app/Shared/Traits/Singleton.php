<?php

namespace App\Shared\Traits;

trait Singleton
{
    private static ?self $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
