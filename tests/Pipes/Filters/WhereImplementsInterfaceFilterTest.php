<?php

namespace Juampi92\Phecks\Tests\Pipes\Filters;

use Illuminate\Support\Enumerable;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereImplementsInterfaceFilter;
use Juampi92\Phecks\Tests\Pipes\Filters\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\Pipes\Filters\stubs\SimpleClassForReflection;
use Juampi92\Phecks\Tests\Pipes\Filters\stubs\SimpleInterface;
use Juampi92\Phecks\Tests\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class WhereImplementsInterfaceFilterTest extends TestCase
{
    public function test_it_should_not_filter_when_implementing_interface(): void
    {
        $class = ReflectionClass::createFromName(SimpleClassForReflection::class);

        $filter = new WhereImplementsInterfaceFilter(SimpleInterface::class);

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(SimpleClassForReflection::class, $result->first()->getName());
    }

    public function test_it_should_not_filter_when_parent_implements_interface(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        $filter = new WhereImplementsInterfaceFilter(Enumerable::class);

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(ClassWithImports::class, $result->first()->getName());
    }

    public function test_it_should_filter_out_if_not_implemented(): void
    {
        $class = ReflectionClass::createFromName(SimpleClassForReflection::class);

        $filter = new WhereImplementsInterfaceFilter(Enumerable::class);

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }
}
