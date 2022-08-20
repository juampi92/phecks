<?php

namespace Juampi92\Phecks\Tests\Feature\stubs\App\Console;

use Illuminate\Console\Command;

// Command class with Command suffix.
class FirstCommand extends Command
{
    public function handle(): int
    {
        return 0;
    }
}
