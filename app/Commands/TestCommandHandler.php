<?php

namespace App\Commands;

class TestCommandHandler
{
    public function handle(TestCommand $command): string
    {
        return "Handled: {$command->name} with value {$command->value}";
    }
}
