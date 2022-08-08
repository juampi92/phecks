<?php

namespace Juampi92\Phecks\Tests\Extractors\stubs;

class SimpleClassForReflection
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
