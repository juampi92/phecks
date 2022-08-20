<?php

namespace Juampi92\Phecks\Tests\Unit\Support;

use Juampi92\Phecks\Support\PathNormalizer;
use Juampi92\Phecks\Tests\Unit\TestCase;

class PathNormalizerTest extends TestCase
{
    /**
     * @param string|callable(): string $path
     * @param string|callable(): string $expected
     * @dataProvider toAbsoluteDataProvider
     */
    public function test_to_absolute_should_work($path, $expected): void
    {
        $this->assertEquals(value($expected), PathNormalizer::toAbsolute(value($path)));
    }

    public function toAbsoluteDataProvider(): array
    {
        return [
            'Absolute to Absolute' => [
                'path' => fn () => base_path('foo/foo.php'), 'expected' => fn () => base_path('foo/foo.php'),
            ],
            'Relative to Absolute' => [
                'path' => './bar/bar.php', 'expected' => fn () => base_path('bar/bar.php'),
            ],
        ];
    }

    /**
     * @param string|callable(): string $path
     * @param string|callable(): string $expected
     * @dataProvider toRelativeDataProvider
     */
    public function test_to_relative_should_work($path, $expected): void
    {
        $this->assertEquals(value($expected), PathNormalizer::toRelative(value($path)));
    }

    public function toRelativeDataProvider(): array
    {
        return [
            'absolute to relative' => [
                'path' => fn () => base_path('foo/bar.php'), 'expected' => './foo/bar.php',
                ],
            'relative to relative' => [
                'path' => './foo/bar.php', 'expected' => './foo/bar.php',
            ],
        ];
    }
}
