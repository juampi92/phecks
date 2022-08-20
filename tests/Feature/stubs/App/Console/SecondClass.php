<?php

namespace Juampi92\Phecks\Tests\Feature\stubs\App\Console;

use Juampi92\Phecks\Tests\Feature\stubs\App\BaseCommand;

// This command class doesn't have the Command suffix.
class SecondClass extends BaseCommand
{
    public function handle(): int
    {
        return 0;
    }
}
