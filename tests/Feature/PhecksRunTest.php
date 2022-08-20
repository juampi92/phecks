<?php

namespace Juampi92\Phecks\Tests\Feature;

use Juampi92\Phecks\Tests\Feature\stubs\Checks\AlwaysPassingCheck;
use Juampi92\Phecks\Tests\Feature\stubs\Checks\ConsoleClassesMustBeSuffixedWithCommandCheck;

class PhecksRunTest extends TestCase
{
    public function test_should_pass_when_there_are_no_errors(): void
    {
        // Arrange
        config(['phecks.checks' => [
            AlwaysPassingCheck::class,
        ]]);

        // Act
        $exitCode = $this->artisan('phecks:run')->run();

        // Assert
        $this->assertEquals(0, $exitCode);
    }

    public function test_should_catch_errors(): void
    {
        // Arrange
        config(['phecks.checks' => [
            ConsoleClassesMustBeSuffixedWithCommandCheck::class,
        ]]);

        // Act
        $exitCode = $this->artisan('phecks:run')->run();

        // Assert
        $this->assertEquals(1, $exitCode);
    }
}
