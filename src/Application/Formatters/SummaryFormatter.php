<?php

namespace Juampi92\Phecks\Application\Formatters;

use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SummaryFormatter implements Formatter
{
    private OutputInterface $output;

    public function __construct(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->output = $output;
    }

    public function format(ViolationsCollection $violations): void
    {
        $this->output->writeln('');

        $violations
            ->groupBy(fn (Violation $violation): string => $violation->getIdentifier())
            // @phpstan-ignore-next-line
            ->each(function (ViolationsCollection $violations, string $target): void {
                $this->output->writeln("<bg=red> Error </> {$target}. Total: {$violations->count()}");
            });

        $this->output->writeln('');
        $this->output->writeln('Total errors: ' . $violations->count());
    }
}
