<?php

namespace Juampi92\Phecks\Tests\Feature;

use Juampi92\Phecks\Application\Baseline\BaselineCollection;
use Juampi92\Phecks\Application\Baseline\BaselineLoader;
use Mockery;

class PhecksBaselineWarningsTest extends TestCase
{
    public function test_should_print_warnings_for_baseline(): void
    {
        // Arrange
        $baseline = [
            'IdentifierFoo' => [
                './app/ClassA.php' => 1,
                './app/ClassB.php' => 2,
            ],
            'IdentifierBar' => [
                './app/ClassB.php' => 2,
                './app/ClassC.php' => 9,
            ],
        ];
        $this->instance(
            BaselineLoader::class,
            tap(Mockery::mock(BaselineLoader::class), function ($mock) use ($baseline) {
                $mock->shouldReceive('load')->andReturn(new BaselineCollection($baseline));
            }),
        );

        // Act
        $exitCode = $this->artisan('phecks:warnings ./app/ClassA.php ./app/ClassB.php')->run();

        // Assert
        $this->assertEquals(0, $exitCode, 'The command must always return success');
    }
}
