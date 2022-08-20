<?php

namespace Juampi92\Phecks\Tests\Unit\Formatters;

use Juampi92\Phecks\Application\Formatters\GithubFormatter;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationSeverity;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class GithubFormatterTest extends TestCase
{
    public function test_can_render_errors_and_warnings(): void
    {
        // Arrange
        $formatter = new GithubFormatter(
            $input = new ArrayInput([]),
            $output = new BufferedOutput(),
        );

        // Act
        $formatter->format(new ViolationsCollection([
            new Violation(
                'MyError',
                new FileMatch('./app/FileOne.php', 15),
                'Testing error',
                'https://www.foo.bar'
            ),
            new Violation(
                'MyWarning',
                new FileMatch('./app/FileOne.php', 30),
                'Testing warning',
                null,
                ViolationSeverity::WARNING
            ),
        ]));
        [$firstLine, $secondLine] = explode("\n", $output->fetch());

        // Assert
        $this->assertStringStartsWith('::error', $firstLine);
        $this->assertStringContainsString('file=./app/FileOne.php', $firstLine);
        $this->assertStringContainsString('line=15', $firstLine);
        $this->assertStringContainsString('title=MyError', $firstLine);
        $this->assertStringEndsWith('::Testing error', $firstLine);

        $this->assertStringStartsWith('::warning', $secondLine);
        $this->assertStringContainsString('file=./app/FileOne.php', $secondLine);
        $this->assertStringContainsString('line=30', $secondLine);
        $this->assertStringContainsString('title=MyWarning', $secondLine);
        $this->assertStringEndsWith('::Testing warning', $secondLine);
    }
}
