<?php

namespace Juampi92\Phecks\Application\Formatters;

use Juampi92\Phecks\Application\Contracts\Formatter;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GithubActionFormatter implements Formatter
{
    protected OutputInterface $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
    }

    public function format(ViolationsCollection $violations): void
    {
        $violations->each(function (Violation $violation) {
            $file = $this->getRelativePath($violation->file->file);
            $line = $violation->file->line ?? 1;
            $title = $violation->getIdentifier();
            $message = $violation->getExplanation();

            $this->output->writeln(sprintf(
                '::error file=%s,line=%s,title=%s::%s',
                $this->escapeData($file),
                $line,
                $title,
                $this->escapeData($message ?: ''),
            ));
        });
    }

    private function getRelativePath(string $file): string
    {
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
    }

    private function escapeData(string $data): string
    {
        return strtr($data, [
            "\r" => '%0D',
            "\n" => '%0A',
        ]);
    }
}
