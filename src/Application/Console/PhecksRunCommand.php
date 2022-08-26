<?php

namespace Juampi92\Phecks\Application\Console;

use Illuminate\Console\Command;
use Juampi92\Phecks\Application\Baseline\BaselineFilter;
use Juampi92\Phecks\Application\Baseline\BaselineGenerator;
use Juampi92\Phecks\Application\Formatters\FormatResolver;
use Juampi92\Phecks\Domain\CheckRunner;

class PhecksRunCommand extends Command
{
    /** @var string */
    protected $signature = 'phecks:run
                                    {--generate-baseline : Will run and generate the baseline.}
                                    {--ignore-baseline : Will run and show all errors.}
                                    {--format=table : Pick a formatter.}';

    /** @var string */
    protected $description = 'Runs the checks.';

    public function handle(CheckRunner $checkRunner, BaselineFilter $baselineFilter, BaselineGenerator $baselineGenerator): int
    {
        $violations = $checkRunner->run();

        if (!$this->option('ignore-baseline') && !$this->option('generate-baseline')) {
            $violations = $baselineFilter->filter($violations);
        }

        if ($this->option('generate-baseline')) {
            $baselineGenerator->generate($violations);

            $this->info('Baseline generated correctly.');
            $this->info($violations->count() . ' violations stored.');

            return self::SUCCESS;
        }

        $formatter = FormatResolver::resolve($this->option('format'), $this->input, $this->getOutput());
        $formatter->format($violations);

        if ($violations->whereError()->isNotEmpty()) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
