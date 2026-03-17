<?php

namespace App\Shared\Commands;

class TestCommandHandler
{
    public function handle(TestCommand $command): string
    {
        return "Handled: {$command->name} with value {$command->value}";
    }
}
