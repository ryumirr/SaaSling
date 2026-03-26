<?php

namespace App\Shared\Contracts;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
