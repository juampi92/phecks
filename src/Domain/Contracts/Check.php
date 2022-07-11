<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Violations\Violation;

interface Check
{
    /**
     * @return Collection<Violation>
     */
    public function run(): Collection;
}
