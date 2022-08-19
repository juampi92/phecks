<?php

namespace Juampi92\Phecks\Tests\Pipes\Extractors;

use Juampi92\Phecks\Domain\Pipes\Extractors\NamespaceExtractor;
use Juampi92\Phecks\Tests\Pipes\Extractors\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class NamespaceExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);

        /** @var NamespaceExtractor $extractor */
        $extractor = resolve(NamespaceExtractor::class);

        $result = $extractor($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [
                'Juampi92\Phecks\Tests\Pipes\Extractors\stubs',
            ],
            $result->all(),
        );
    }
}
