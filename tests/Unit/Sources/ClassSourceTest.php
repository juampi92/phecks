<?php

namespace Juampi92\Phecks\Tests\Unit\Sources;

use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\ClassSource;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class ClassSourceTest extends TestCase
{
    public function test_should_work(): void
    {
        $result = resolve(ClassSource::class)
            ->directory('./tests/Unit/Sources/stubs')
            ->run();

        $this->assertGreaterThan(1, $result->count());
        /** @var ReflectionClass $class */
        $class = $result->getItems()->first()->value;
        $this->assertInstanceOf($class->getName(), resolve($class->getName()));

        $namespaces = $result
            ->getItems()
            ->map(function (MatchValue $match) {
                return $match->value->getNamespaceName();
            })
            ->unique();

        $this->assertCount(1, $namespaces, 'It should not be recursive');
    }

    public function test_should_work_recursively(): void
    {
        $result = resolve(ClassSource::class)
            ->directory('./tests/Unit/Sources/stubs')
            ->recursive()
            ->run();

        $this->assertGreaterThan(1, $result->count());

        /** @var ReflectionClass $class */
        $class = $result->getItems()->first()->value;
        $this->assertInstanceOf($class->getName(), resolve($class->getName()));

        $namespaces = $result
            ->getItems()
            ->map(function (MatchValue $match) {
                return $match->value->getNamespaceName();
            })
            ->unique();

        $this->assertCount(2, $namespaces, 'It should not be recursive');
    }
}
