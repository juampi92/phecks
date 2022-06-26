<?php

namespace Juampi92\Phecks\Tests\Extractors;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Extractors\ClassExtractor;
use Juampi92\Phecks\Tests\TestCase;

class ClassExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $path = './tests/Extractors/stubs/ClassWithImports.php';
        $file = new FileMatch($path);

        /** @var ClassExtractor $extractor */
        $extractor = resolve(ClassExtractor::class);

        $result = $extractor->extract($file);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [
                stubs\ClassWithImports::class,
            ],
            $result->all(),
        );
    }
}
