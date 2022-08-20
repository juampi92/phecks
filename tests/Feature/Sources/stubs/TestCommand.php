<?php

namespace Juampi92\Phecks\Tests\Feature\Sources\stubs;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'my-command:foo:bar {argument? : This is an argument} {--O|option : This is an option}';

    protected $description = 'FooBar';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
