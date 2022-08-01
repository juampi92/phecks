<?php

namespace Juampi92\Phecks\Tests\Violations;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;
use Juampi92\Phecks\Tests\TestCase;
use RuntimeException;

class ViolationBuilderTest extends TestCase
{
    public function test_should_create_successfully_with_identifier(): void
    {
        // Arrange
        $violation = ViolationBuilder::make()
            ->file(new FileMatch($file = './file/MyFile.php', $line = 55))
            ->identifier($identifier = 'MyIdentifier')
            ->build();

        $this->assertInstanceOf(Violation::class, $violation);

        $this->assertEquals($identifier, $violation->getIdentifier());
        $this->assertEquals($file, $violation->getTarget());
        $this->assertEquals("$file:$line", $violation->getLocation());
    }

    public function test_should_create_successfully_with_check(): void
    {
        // Arrange
        $violation = ViolationBuilder::make()
            ->file(new FileMatch($file = './file/MyFile.php', $line = 55))
            ->check($check = new class() implements Check {
                public function run(): Collection
                {
                    return collect();
                }
            })
            ->build();

        $this->assertInstanceOf(Violation::class, $violation);

        $this->assertEquals(class_basename($check), $violation->getIdentifier());
        $this->assertEquals($file, $violation->getTarget());
        $this->assertEquals("$file:$line", $violation->getLocation());
    }

    /**
     * @dataProvider invalidCreationDataProvider
     */
    public function test_should_fail_when_invalid_data(ViolationBuilder $builder, string $exception): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectDeprecationMessageMatches("/$exception/i");

        $builder->build();
    }

    public function invalidCreationDataProvider(): array
    {
        return [
            'Only file' => [
                'builder' => ViolationBuilder::make()->file(new FileMatch('./tests/Testing.php')),
                'exception' => "The violation\'s identifier is required",
            ],
            'No file' => [
                'builder' => ViolationBuilder::make()->identifier('FooBar'),
                'exception' => "The file is needed when building a violation",
            ],
        ];
    }
}

