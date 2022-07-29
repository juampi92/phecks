<?php

namespace Juampi92\Phecks\Tests\Extractors;

use Juampi92\Phecks\Domain\Extractors\ReflectionMethodExtractor;
use Juampi92\Phecks\Tests\Extractors\stubs\SimpleClassForReflection;
use Juampi92\Phecks\Tests\TestCase;
use ReflectionClass;
use ReflectionMethod;

class ReflectionMethodExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        // Arrange
        $reflectionClass = new ReflectionClass(SimpleClassForReflection::class);

        $extractor = new ReflectionMethodExtractor();

        // Act
        $result = $extractor->extract($reflectionClass);

        // Assert
        $this->assertEquals(2, $result->count());

        /** @var ReflectionMethod $methodFoo */
        $methodFoo = $result->first();
        /** @var ReflectionMethod $methodBar */
        $methodBar = $result->last();

        $this->assertInstanceOf(ReflectionMethod::class, $methodFoo);
        $this->assertInstanceOf(ReflectionMethod::class, $methodBar);

        $this->assertEquals('foo', $methodFoo->getName());
        $this->assertEquals(true, $methodFoo->isPublic());

        $this->assertEquals('bar', $methodBar->getName());
        $this->assertEquals(true, $methodBar->isPrivate());
    }

    public function test_it_should_filter_methods(): void
    {
        // Arrange
        $reflectionClass = new ReflectionClass(SimpleClassForReflection::class);

        $extractor = new ReflectionMethodExtractor(ReflectionMethod::IS_PRIVATE);

        // Act
        $result = $extractor->extract($reflectionClass);

        // Assert
        $this->assertEquals(1, $result->count());

        /** @var ReflectionMethod $method */
        $method = $result->first();

        $this->assertEquals('bar', $method->getName());

        // --- Filter Public methods

        // Arrange
        $extractor = new ReflectionMethodExtractor(ReflectionMethod::IS_PUBLIC);

        // Act
        $result = $extractor->extract($reflectionClass);

        // Assert
        $this->assertEquals(1, $result->count());

        /** @var ReflectionMethod $method */
        $method = $result->first();

        $this->assertEquals('foo', $method->getName());
    }
}
