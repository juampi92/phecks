<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Juampi92\Phecks\Domain\MatchCollection;

/**
 * @template TMatch
 */
interface Source
{
    /**
     * @return MatchCollection<TMatch>
     */
    public function run(): MatchCollection;
}
