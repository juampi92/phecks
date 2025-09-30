<?php

namespace Juampi92\Phecks\Application\Formatters;

use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationSeverity;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JsonFormatter implements Formatter
{
    protected OutputInterface $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }

    public function format(ViolationsCollection $violations): void
    {
        $report = [
            'package' => 'phecks',
            'violations' => $violations->toArray(),
            'summary' => $this->buildSummary($violations),
            'total' => $violations->count(),
        ];

        $this->output->writeln(json_encode($report, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    /**
     * @return array{errors: int, warnings: int}
     */
    private function buildSummary(ViolationsCollection $violations): array
    {
        $groupedBySeverity = $violations->groupBy->getSeverity();

        return [
            'errors' => $groupedBySeverity->get(ViolationSeverity::ERROR)?->count() ?? 0,
            'warnings' => $groupedBySeverity->get(ViolationSeverity::WARNING)?->count() ?? 0,
        ];
    }
}
