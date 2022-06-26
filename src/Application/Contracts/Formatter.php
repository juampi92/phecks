<?php

namespace Juampi92\Phecks\Application\Contracts;

use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface Formatter
{
    public function __construct(InputInterface $input, OutputInterface $output);

    public function format(ViolationsCollection $violations): void;
}
