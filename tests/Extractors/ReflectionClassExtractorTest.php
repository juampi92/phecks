<?php

namespace Juampi92\Phecks\Tests\Extractors;

use Juampi92\Phecks\Domain\Extractors\ReflectionClassExtractor;
use Juampi92\Phecks\Tests\Extractors\stubs\SimpleClassForReflection;
use Juampi92\Phecks\Tests\TestCase;
use ReflectionClass;

class ReflectionClassExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        // Arrange
        $class = SimpleClassForReflection::class;

        $extractor = new ReflectionClassExtractor();

        // Act
        $result = $extractor->extract($class);

        // Assert
        $this->assertEquals(1, $result->count());

        /** @var ReflectionClass $reflectionClass */
        $reflectionClass = $result->first();

        $this->assertInstanceOf(ReflectionClass::class, $reflectionClass);
        $this->assertEquals('SimpleClassForReflection', $reflectionClass->getShortName());
    }
}
