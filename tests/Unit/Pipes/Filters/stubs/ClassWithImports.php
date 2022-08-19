<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Filters\stubs;

use Juampi92\Phecks\Domain\MatchString;

class ClassWithImports extends MyCollection
{
    public function testMethod(): MatchString
    {
        return new MatchString('hi');
    }
}
