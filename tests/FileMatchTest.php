<?php

namespace Juampi92\Phecks\Tests;

use Juampi92\Phecks\Domain\DTOs\FileMatch;

class FileMatchTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(realpath(__DIR__.'/..') ?: __DIR__);
    }

    public function test_it_creates_from_relative_paths(): void
    {
        $file = new FileMatch('./tests/FileMatchTest.php', 1);

        $this->assertEquals('./tests/FileMatchTest.php', $file->file);
    }

    public function test_it_normalizes_from_absolute_paths(): void
    {
        $file = new FileMatch(realpath(__DIR__.'/../tests/FileMatchTest.php') ?: '', 1);

        $this->assertEquals('./tests/FileMatchTest.php', $file->file);
    }
}