<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;

/**
 * @template TMatch
 */
interface Check
{
    /**
     * @return MatchCollection<TMatch>
     */
    public function getMatches(): MatchCollection;

    /**
     * @param TMatch $match
     * @param FileMatch $file
     * @return array<ViolationBuilder>
     */
    public function processMatch($match, FileMatch $file): array;
}
