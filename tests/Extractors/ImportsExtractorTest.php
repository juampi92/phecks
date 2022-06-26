<?php

namespace Juampi92\Phecks\Tests\Extractors;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Extractors\ImportsExtractor;
use Juampi92\Phecks\Tests\TestCase;
use Symfony\Component\Finder\SplFileInfo;

class ImportsExtractorTest extends TestCase
{
    public function test_it_should_work(): void
    {
        $path = './tests/Extractors/stubs/ClassWithImports.php';
        $file = new FileMatch(file: $path);

        /** @var ImportsExtractor $extractor */
        $extractor = resolve(ImportsExtractor::class);

        $result = $extractor->extract($file);

        $this->assertEquals(2, $result->count());
        $this->assertEquals(
            [
                \Illuminate\Support\Collection::class,
                \Juampi92\Phecks\Domain\MatchString::class,
            ],
            $result->all(),
        );
    }
}
