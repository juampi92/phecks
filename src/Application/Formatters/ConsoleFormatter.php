<?php

namespace Juampi92\Phecks\Application\Formatters;

use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleFormatter implements Formatter
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
            ->groupBy->getTarget()
            ->each(function (ViolationsCollection $violations, string $target) {
                $this->output->writeln('');
                $this->output->writeln("<bg=red> ◼ {$target} </>");
                $this->output->writeln('<options=bold>Errors</>: ' . $violations->count());

                $violations->each(function (Violation $violation) {
                    $this->output->writeln('');
                    $this->output->writeln('    <options=bold>Error:</> ' . $violation->getIdentifier());

                    if ($location = $violation->getLocation()) {
                        $this->output->writeln('    <options=bold>Location:</> ' . $location);
                    }

                    if ($explanation = $violation->getExplanation()) {
                        $this->output->writeln('    <options=bold>Explanation:</> ' . $explanation);
                    }
                });
            });

        $this->output->writeln('');
        $this->output->writeln('Total errors: ' . $violations->count());
    }
}
