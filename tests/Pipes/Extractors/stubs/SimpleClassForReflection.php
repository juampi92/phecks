<?php

namespace Juampi92\Phecks\Tests\Pipes\Extractors\stubs;

use Juampi92\Phecks\Tests\Pipes\Extractors\stubs\SimpleInterface as MyInterface;

class SimpleClassForReflection implements MyInterface
{
    public function foo(): string
    {
        return 'hello world';
    }

    private function bar(): bool
    {
        return true;
    }
}
