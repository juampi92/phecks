<?php

namespace Juampi92\Phecks\Tests\Unit\Formatters;

use Juampi92\Phecks\Application\Formatters\JsonFormatter;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationSeverity;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class JsonFormatterTest extends TestCase
{
    public function test_can_render_empty_violations(): void
    {
        // Arrange

        $formatter = new JsonFormatter(
            new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        $violations = ViolationsCollection::empty();

        $expectedReport = $this->loadStub('json-empty-violations.json');

        // Act

        $formatter->format($violations);

        // Assert

        $actualReport = json_decode($output->fetch(), true);

        $this->assertEquals($expectedReport, $actualReport);
    }

    public function test_can_render_single_error_violation(): void
    {
        // Arrange

        $formatter = new JsonFormatter(
            new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        $violations = new ViolationsCollection([
            new Violation(
                'MyError',
                new FileMatch('./app/FileOne.php', 15),
                'Testing error',
                'https://www.foo.bar'
            ),
        ]);

        $expectedReport = $this->loadStub('json-single-error.json');

        // Act

        $formatter->format($violations);

        // Assert

        $actualReport = json_decode($output->fetch(), true);

        $this->assertEquals($expectedReport, $actualReport);
    }

    public function test_can_render_multiple_violations_with_mixed_severities(): void
    {
        // Arrange

        $formatter = new JsonFormatter(
            new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        $violations = new ViolationsCollection([
            new Violation(
                'FirstError',
                new FileMatch('./app/FileOne.php', 15),
                'First error message',
                'https://docs.example.com'
            ),
            new Violation(
                'FirstWarning',
                new FileMatch('./app/FileTwo.php', 30),
                'First warning message',
                null,
                ViolationSeverity::WARNING
            ),
            new Violation(
                'SecondError',
                new FileMatch('./app/FileThree.php', 45),
                'Second error message',
                null,
                ViolationSeverity::ERROR
            ),
        ]);

        $expectedReport = $this->loadStub('json-mixed-violations.json');

        // Act

        $formatter->format($violations);

        // Assert

        $actualReport = json_decode($output->fetch(), true);

        $this->assertEquals($expectedReport, $actualReport);
    }

    public function test_can_render_violation_without_line_number(): void
    {
        // Arrange

        $formatter = new JsonFormatter(
            new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        $violations = new ViolationsCollection([
            new Violation(
                'NoLineError',
                new FileMatch('./app/FileOne.php'),
                'Error without line number',
                null
            ),
        ]);

        $expectedReport = $this->loadStub('json-violation-without-line.json');

        // Act

        $formatter->format($violations);

        // Assert

        $actualReport = json_decode($output->fetch(), true);

        $this->assertEquals($expectedReport, $actualReport);
    }

    public function test_output_is_valid_json(): void
    {
        // Arrange

        $formatter = new JsonFormatter(
            new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        $violations = new ViolationsCollection([
            new Violation(
                'TestError',
                new FileMatch('./app/Test.php', 10),
                'Test message',
                null
            ),
        ]);

        // Act

        $formatter->format($violations);

        $jsonOutput = $output->fetch();

        // Assert

        $this->assertNotFalse(json_decode($jsonOutput));

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    /*
     * Helpers.
     */

    private function loadStub(string $filename): array
    {
        $content = file_get_contents(__DIR__ . '/stubs/' . $filename);

        return json_decode($content, true);
    }
}
