<?php

namespace Juampi92\Phecks\Tests\Pipes\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereDoesNotExtendClassFilter;
use Juampi92\Phecks\Tests\Pipes\Filters\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\Pipes\Filters\stubs\MyCollection;
use Juampi92\Phecks\Tests\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class WhereDoesNotExtendClassFilterTest extends TestCase
{
    public function test_it_should_filter_out_extended_classes(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereDoesNotExtendClassFilter(MyCollection::class);

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }

    public function test_it_should_filter_extends_of_extends(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereDoesNotExtendClassFilter(Collection::class);

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }

    public function test_it_should_not_filter_out_if_not_present(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereDoesNotExtendClassFilter(Stringable::class);

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(ClassWithImports::class, $result->first()->getName());
    }
}
