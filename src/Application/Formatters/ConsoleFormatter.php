<?php

namespace Juampi92\Phecks\Application\Formatters;

use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleFormatter implements Formatter
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

        $violations
            ->groupBy(fn (Violation $violation): string => $violation->getTarget())
            ->each(function (ViolationsCollection $violations, string $target) {
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

        $this->style->error("Found {$violations->count()} errors");
    }
}
