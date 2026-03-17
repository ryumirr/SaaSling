<?php

namespace App\Commands;

class TestCommand
{
    public function __construct(
        public readonly string $name,
        public readonly int $value,
    ) {}
}
