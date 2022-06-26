<?php

namespace Juampi92\Phecks\Tests\Sources;

use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\ClassSource;
use Juampi92\Phecks\Tests\TestCase;

class ClassSourceTest extends TestCase
{
    public function test_should_work(): void
    {
        $result = resolve(ClassSource::class)
            ->directory('./tests/Sources/stubs')
            ->run();

        $this->assertGreaterThan(1, $result->count());
        $this->assertInstanceOf($result->getMatches()->first()->value, resolve($result->getMatches()->first()->value));

        $namespaces = $result
            ->getMatches()
            ->map(function (MatchValue $match) {
                $basename = class_basename($match->value);

                return Str::before($match->value, "\\{$basename}");
            })
            ->unique();

        $this->assertCount(1, $namespaces, 'It should not be recursive');
    }

    public function test_should_work_recursively(): void
    {
        $result = resolve(ClassSource::class)
            ->directory('./tests/Sources/stubs')
            ->recursive()
            ->run();

        $this->assertGreaterThan(1, $result->count());
        $this->assertInstanceOf($result->getMatches()->first()->value, resolve($result->getMatches()->first()->value));

        $namespaces = $result
            ->getMatches()
            ->map(function (MatchValue $match) {
                $basename = class_basename($match->value);

                return Str::before($match->value, "\\{$basename}");
            })
            ->unique();

        $this->assertCount(2, $namespaces, 'It should not be recursive');
    }
}
