<?php

namespace Juampi92\Phecks\Tests\Extractors;

use Juampi92\Phecks\Domain\Extractors\NamespaceExtractor;
use Juampi92\Phecks\Tests\Extractors\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\TestCase;

class NamespaceExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $class = ClassWithImports::class;

        /** @var NamespaceExtractor $extractor */
        $extractor = resolve(NamespaceExtractor::class);

        $result = $extractor->extract($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [
                'Juampi92\Phecks\Tests\Extractors\stubs',
            ],
            $result->all(),
        );
    }
}
