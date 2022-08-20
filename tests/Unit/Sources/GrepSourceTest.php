<?php

namespace Juampi92\Phecks\Tests\Unit\Sources;

use Juampi92\Phecks\Domain\Sources\Flags\GrepFlags;
use Juampi92\Phecks\Domain\Sources\GrepSource;
use Juampi92\Phecks\Tests\Unit\TestCase;

class GrepSourceTest extends TestCase
{
    public function test_should_work(): void
    {
        /** @var GrepSource $source */
        $source = resolve(GrepSource::class);
        $result = $source
            ->files('./tests/Unit/Sources/stubs')
            ->pattern('extends')
            ->run();

        $this->assertEquals(1, $result->count());
        $this->assertEquals('class SubclassC extends ClassA', $result->getItems()->first()->value->context);
    }

    public function test_should_work_with_flags(): void
    {
        /** @var GrepSource $source */
        $source = resolve(GrepSource::class);
        $result = $source
            ->files('./tests/Unit/Sources/stubs')
            ->setFlags([
                GrepFlags::DEFERENCE_RECURSIVE,
                GrepFlags::WITH_FILENAME,
                GrepFlags::LINE_NUMBER,
            ])
            ->addFlags([GrepFlags::IGNORE_CASE])
            ->addFlags(GrepFlags::FIXED_STRINGS, GrepFlags::EXTENDED_REGEXP)
            ->pattern('extends')
            ->run();

        $this->assertEquals(1, $result->count());
        $this->assertEquals('class SubclassC extends ClassA', $result->getItems()->first()->value->context);
    }
}
