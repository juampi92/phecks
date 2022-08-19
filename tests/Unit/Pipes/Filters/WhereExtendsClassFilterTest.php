<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereExtendsClassFilter;
use Juampi92\Phecks\Tests\Unit\Pipes\Filters\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\Unit\Pipes\Filters\stubs\MyCollection;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class WhereExtendsClassFilterTest extends TestCase
{
    public function test_it_should_not_filter_the_specified_class(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereExtendsClassFilter(MyCollection::class);

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(ClassWithImports::class, $result->first()->getName());
    }

    public function test_it_should_not_filter_the_parent_of_the_parent(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereExtendsClassFilter(Collection::class);

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(ClassWithImports::class, $result->first()->getName());
    }

    public function test_it_should_filter_out_if_not_parent(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereExtendsClassFilter(Stringable::class);

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }
}
