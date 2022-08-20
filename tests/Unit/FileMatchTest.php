<?php

namespace Juampi92\Phecks\Tests\Unit;

use Juampi92\Phecks\Domain\DTOs\FileMatch;

class FileMatchTest extends TestCase
{
    public function test_it_creates_from_relative_paths(): void
    {
        $file = new FileMatch('./tests/Unit/FileMatchTest.php', 1);

        $this->assertEquals('./tests/Unit/FileMatchTest.php', $file->file);
    }

    public function test_it_normalizes_from_absolute_paths(): void
    {
        $file = new FileMatch(realpath(__DIR__.'/../../tests/Unit/FileMatchTest.php') ?: '', 1);

        $this->assertEquals('./tests/Unit/FileMatchTest.php', $file->file);
    }
}
