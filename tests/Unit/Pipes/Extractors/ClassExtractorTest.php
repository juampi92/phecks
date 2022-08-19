<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Extractors;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Pipes\Extractors\ClassExtractor;
use Juampi92\Phecks\Tests\Unit\TestCase;

class ClassExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $path = './tests/Unit/Pipes/Extractors/stubs/ClassWithImports.php';
        $file = new FileMatch($path);

        /** @var ClassExtractor $extractor */
        $extractor = resolve(ClassExtractor::class);

        $result = $extractor($file);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(
            [
                \Juampi92\Phecks\Tests\Unit\Pipes\Extractors\stubs\ClassWithImports::class,
            ],
            $result->map->getName()->all(),
        );
    }
}
