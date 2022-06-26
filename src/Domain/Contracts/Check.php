<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

interface Check
{
    public function run(): ViolationsCollection;
}
