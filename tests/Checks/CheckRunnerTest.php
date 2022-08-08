<?php

namespace Juampi92\Phecks\Tests\Checks;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Juampi92\Phecks\Domain\CheckRunner;
use Juampi92\Phecks\Tests\Checks\stubs\EmptyCheck;
use Juampi92\Phecks\Tests\Checks\stubs\SingleViolationCheck;
use Juampi92\Phecks\Tests\TestCase;

class CheckRunnerTest extends TestCase
{
    public function test_it_should_work(): void
    {
        // Arrange
        $this->instance(
            RepositoryContract::class,
            new Repository([
                'phecks' => [
                    'checks' => [
                        EmptyCheck::class,
                        SingleViolationCheck::class,
                    ],
                ],
            ])
        );

        // Act
        $violations = resolve(CheckRunner::class)->run();

        // Assert
        $this->assertCount(1, $violations);
        $this->assertEquals('SingleViolationCheck', $violations[0]->getIdentifier());
        $this->assertEquals('./test/MyMatch.php', $violations[0]->getTarget());
        $this->assertEquals('./test/MyMatch.php:55', $violations[0]->getLocation());
        $this->assertNull($violations[0]->getTip());
    }
}
