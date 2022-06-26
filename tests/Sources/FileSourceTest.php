<?php

namespace Juampi92\Phecks\Tests\Sources;

use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\FileSource;
use Juampi92\Phecks\Tests\TestCase;
use Symfony\Component\Finder\SplFileInfo;

class FileSourceTest extends TestCase
{
    public function test_it_should_work_non_recursive(): void
    {
        $result = resolve(FileSource::class)
            ->directory('./tests/Sources/stubs')
            ->run();

        $this->assertGreaterThan(1, $result->count());

        $paths = $result
            ->getMatches()
            ->map(fn (MatchValue $match) => dirname($match->file->file))
            ->unique();

        $this->assertCount(1, $paths, 'It should not be recursive');
    }

    public function test_it_should_work_recursive(): void
    {
        $result = resolve(FileSource::class)
            ->directory('./tests/Sources/stubs')
            ->recursive()
            ->run();

        $this->assertGreaterThan(1, $result->count());

        $paths = $result
            ->getMatches()
            ->map(fn (MatchValue $match) => dirname($match->file->file))
            ->unique();

        $this->assertCount(2, $paths, 'It should be recursive');
    }
}
