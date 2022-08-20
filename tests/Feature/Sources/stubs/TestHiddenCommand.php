<?php

namespace Juampi92\Phecks\Tests\Feature\Sources\stubs;

use Illuminate\Console\Command;

class TestHiddenCommand extends Command
{
    protected $signature = 'my-command:foo:hidden';

    protected $description = 'FooHidden';

    protected $hidden = true;

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
