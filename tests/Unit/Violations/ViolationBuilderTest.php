<?php

namespace Juampi92\Phecks\Tests\Unit\Violations;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;
use Juampi92\Phecks\Tests\Unit\TestCase;
use RuntimeException;

class ViolationBuilderTest extends TestCase
{
    public function test_should_create_successfully_with_identifier(): void
    {
        // Arrange
        $violation = ViolationBuilder::make()
            ->message('This is important! Fix it!')
            ->setIdentifier($identifier = 'MyIdentifier')
            ->build($this->getCheck(), new FileMatch($file = './file/MyFile.php', $line = 55));

        $this->assertInstanceOf(Violation::class, $violation);

        $this->assertEquals($identifier, $violation->getIdentifier());
        $this->assertEquals($file, $violation->getTarget());
        $this->assertEquals("$file:$line", $violation->getLocation());
    }

    public function test_should_create_successfully_with_check(): void
    {
        // Arrange
        $violation = ViolationBuilder::make()
            ->message('This is important! Fix it!')
            ->build($check = $this->getCheck(), new FileMatch($file = './file/MyFile.php', $line = 55));

        $this->assertInstanceOf(Violation::class, $violation);

        $this->assertEquals(class_basename($check), $violation->getIdentifier());
        $this->assertEquals($file, $violation->getTarget());
        $this->assertEquals("$file:$line", $violation->getLocation());
    }

    public function test_should_fail_when_invalid_data(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches("/The violation must have a message/i");

        ViolationBuilder::make()->build($this->getCheck(), new FileMatch('./tests/Random.php'));
    }

    private function getCheck(): Check
    {
        return new class() implements Check {
            public function getMatches(): MatchCollection
            {
                return collect();
            }

            public function processMatch($match, FileMatch $file): array
            {
                return [];
            }
        };
    }
}
