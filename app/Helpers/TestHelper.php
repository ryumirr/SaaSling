<?php

namespace App\Helpers;

class TestHelper
{
    public static function greet(string $name): string
    {
        return "Hello, {$name}!";
    }

    public static function add(int $a, int $b): int
    {
        return $a + $b;
    }
}
