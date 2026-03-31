<?php

namespace App\Shared\Infrastructure;

use App\Shared\Contracts\UuidGeneratorInterface;
use Illuminate\Support\Str;

class RamseyUuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return (string) Str::uuid();
    }
}
