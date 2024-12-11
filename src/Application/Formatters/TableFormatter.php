<?php

namespace Juampi92\Phecks\Application\Formatters;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationSeverity;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TableFormatter implements Formatter
{
    private SymfonyStyle $style;

    public function __construct(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->style = new SymfonyStyle($input, $output);
    }

    public function format(ViolationsCollection $violations): void
    {
        if ($violations->isEmpty()) {
            $this->style->success('No errors');

            return;
        }

        /** @var Collection<string, ViolationsCollection> $violationsGroupedByTarget */
        $violationsGroupedByTarget = $violations
            ->groupBy(fn (Violation $violation): string => $violation->getTarget());

        $violationsGroupedByTarget->each(function (ViolationsCollection $violations, string $target) {
            $this->style->table(
                ['Line', $target],
                $violations->map(function (Violation $violation): array {
                    $errorLine = sprintf("%s  <options=bold>(%s)</>", $violation->getMessage(), $violation->getIdentifier());
                    $urlLine = $violation->getUrl() ? "\n<href={$violation->getUrl()}>💡 Read more.</>" : '';

                    return [
                        $violation->getLine() ?: '-',
                        $errorLine . $urlLine,
                    ];
                })->all()
            );
        });

        /** @var Collection<string, ViolationsCollection> $violationsGroupedBySeverity */
        $violationsGroupedBySeverity = $violations->groupBy(fn (Violation $violation): string => $violation->getSeverity());

        /** @var Collection<ViolationSeverity::*, int> $severitiesCount */
        $severitiesCount = $violationsGroupedBySeverity->map(fn (Collection $violationsCollection): int => $violationsCollection->count());

        $errorsCount = (int) $severitiesCount->get(ViolationSeverity::ERROR, 0);
        if ($errorsCount > 0) {
            $this->style->error("Found {$errorsCount} errors");
        }

        $warningsCount = (int) $severitiesCount->get(ViolationSeverity::WARNING, 0);
        if ($warningsCount > 0) {
            $this->style->error("Found {$warningsCount} errors");
        }
    }
}
