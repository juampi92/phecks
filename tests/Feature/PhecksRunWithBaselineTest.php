<?php

namespace Juampi92\Phecks\Tests\Feature;

use Illuminate\Filesystem\Filesystem;
use Juampi92\Phecks\Tests\Feature\stubs\Checks\ConsoleClassesMustBeSuffixedWithCommandCheck;
use Mockery;

class PhecksRunWithBaselineTest extends TestCase
{
    public function test_should_generate_the_baseline(): void
    {
        // Arrange
        config([
            'phecks.checks' => [
                ConsoleClassesMustBeSuffixedWithCommandCheck::class,
            ],
            'phecks.baseline' => $path = '.phecks-random.baseline.json',
        ]);

        $this->instance(Filesystem::class, $fileSpy = Mockery::spy(Filesystem::class));

        // Act
        $exitCode = $this->artisan('phecks:run', ['--generate-baseline' => true])->run();

        // Assert
        $this->assertEquals(0, $exitCode);

        $fileSpy->shouldHaveReceived('put')
            ->once()
            ->withArgs(function ($baselinePath, $baselineContent) use ($path) {
                if ($baselinePath !== $path) {
                    return false;
                }

                $checkName = class_basename(ConsoleClassesMustBeSuffixedWithCommandCheck::class);

                $baseline = json_decode($baselineContent, true);

                return
                    count(array_keys($baseline)) === 1
                    && isset($baseline[$checkName])
                    && $baseline[$checkName] === [
                        "./tests/Feature/stubs/App/Console/SecondClass.php" => 1,
                    ];
            });
    }
}
