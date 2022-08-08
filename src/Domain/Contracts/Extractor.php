<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Illuminate\Support\Collection;

/**
 * @template TMatchInput
 * @template TMatchOutput
 */
interface Extractor
{
    /**
     * @param TMatchInput $match
     * @return Collection<array-key, TMatchOutput>
     */
    public function extract($match): Collection;
}
