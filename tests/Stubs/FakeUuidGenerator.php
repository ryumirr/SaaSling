<?php

namespace Tests\Stubs;

use App\Shared\Contracts\UuidGeneratorInterface;

class FakeUuidGenerator implements UuidGeneratorInterface
{
    private int $counter = 0;

    public function generate(): string
    {
        return 'fake-uuid-' . ++$this->counter;
    }
}
