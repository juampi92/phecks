<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Extractors;

use Juampi92\Phecks\Domain\Pipes\Extractors\MethodExtractor;
use Juampi92\Phecks\Tests\Unit\Pipes\Extractors\stubs\SimpleClassForReflection;
use Juampi92\Phecks\Tests\Unit\TestCase;
use ReflectionClass;
use ReflectionMethod;

class MethodExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        // Arrange
        $reflectionClass = new ReflectionClass(SimpleClassForReflection::class);

        $extractor = new MethodExtractor();

        // Act
        $result = $extractor($reflectionClass);

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

    /**
     *
     * @dataProvider methodFilterDataProvider
     */
    public function test_it_should_filter_methods(MethodExtractor $extractor, array $matches): void
    {
        // Arrange
        $reflectionClass = new ReflectionClass(SimpleClassForReflection::class);

        // Act
        $result = $extractor($reflectionClass);

        // Assert
        $this->assertEquals(count($matches), $result->count());

        /** @var ReflectionMethod $method */
        $this->assertEqualsCanonicalizing(
            $matches,
            $result->map->getName()->all()
        );
    }

    public function methodFilterDataProvider(): array
    {
        return [
            'private' => [
                'extractor' => new MethodExtractor(ReflectionMethod::IS_PRIVATE),
                'matches' => [
                    'bar',
                ],
            ],
            'public' => [
                'extractor' => new MethodExtractor(ReflectionMethod::IS_PUBLIC),
                'matches' => [
                    'foo',
                ],
            ],
            'static' => [
                'extractor' => new MethodExtractor(ReflectionMethod::IS_STATIC),
                'matches' => [],
            ],
        ];
    }
}
