<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Extractors\stubs;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\MatchString;

class ClassWithImports extends Collection
{
    public function testMethod(): MatchString
    {
        return new MatchString('hi');
    }
}
