<?php

namespace Juampi92\Phecks\Tests\Unit\Formatters;

use Juampi92\Phecks\Application\Formatters\TableFormatter;
use Juampi92\Phecks\Application\Formatters\FormatResolver;
use Juampi92\Phecks\Application\Formatters\GithubFormatter;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Mockery;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class FormatResolverTest extends TestCase
{
    /**
     * @dataProvider formatterDataProvider
     */
    public function test_should_resolve_the_correct_formatter(?string $formatter, string $expected): void
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $formatter = FormatResolver::resolve($formatter, $input, $output);

        $this->assertInstanceOf($expected, $formatter);
    }

    public function formatterDataProvider(): array
    {
        return [
            'console' => [
                'formatter' => 'console',
                'expected' => TableFormatter::class,
            ],
            'github' => [
                'formatter' => 'github',
                'expected' => GithubFormatter::class,
            ],
        ];
    }

    public function test_should_break_if_formatter_doesnt_exist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/^The specified formatter \'random-foo-bar\' is not supported. Use any of/');

        $input = Mockery::mock(InputInterface::class);
        $output = Mockery::mock(OutputInterface::class);

        FormatResolver::resolve('random-foo-bar', $input, $output);
    }
}
