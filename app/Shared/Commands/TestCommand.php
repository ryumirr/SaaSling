<?php

namespace App\Shared\Commands;

class TestCommand
{
    public function __construct(
        public readonly string $name,
        public readonly int $value,
    ) {}
}
