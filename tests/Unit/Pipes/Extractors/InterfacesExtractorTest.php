<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Extractors;

use Juampi92\Phecks\Domain\Pipes\Extractors\InterfacesExtractor;
use Juampi92\Phecks\Tests\Unit\Pipes\Extractors\stubs\SimpleClassForReflection;
use Juampi92\Phecks\Tests\Unit\Pipes\Extractors\stubs\SimpleInterface;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class InterfacesExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $class = ReflectionClass::createFromName(SimpleClassForReflection::class);

        /** @var InterfacesExtractor $extractor */
        $extractor = resolve(InterfacesExtractor::class);

        $result = $extractor($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [
                SimpleInterface::class,
            ],
            $result->map->getName()->values()->all(),
        );
    }
}
