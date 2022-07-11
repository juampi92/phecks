<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Juampi92\Phecks\Domain\MatchCollection;

interface Source
{
    public function run(): MatchCollection;
}
