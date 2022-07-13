<?php

namespace Juampi92\Phecks\Application\Console;

use Illuminate\Console\Command;
use Juampi92\Phecks\Application\Baseline\BaselineFilter;
use Juampi92\Phecks\Application\Baseline\BaselineGenerator;
use Juampi92\Phecks\Application\Formatters\ConsoleFormatter;
use Juampi92\Phecks\Application\Formatters\FormatResolver;
use Juampi92\Phecks\Domain\CheckRunner;

class PhecksRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phecks:run
                                    {--generate-baseline : Will run and generate the baseline.}
                                    {--ignore-baseline : Will run and show all errors.}
                                    {--format=console : Pick a formatter. console|stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the checks.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $violations = resolve(CheckRunner::class)->run();

        if (!$this->option('ignore-baseline') && !$this->option('generate-baseline')) {
            $violations = resolve(BaselineFilter::class)->filter($violations);
        }

        if ($this->option('generate-baseline')) {
            resolve(BaselineGenerator::class)->generate($violations);

            $this->info('Baseline generated correctly.');
            $this->info($violations->count() . ' violations stored.');

            return self::SUCCESS;
        }

        $formatter = FormatResolver::resolve($this->option('format'), $this->input, $this->getOutput());
        $formatter->format($violations);

        if ($violations->isNotEmpty()) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
