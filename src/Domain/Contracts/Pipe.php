<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Illuminate\Support\Collection;

/**
 * @template TMatchInput
 * @template TMatchOutput
 */
interface Pipe
{
    /**
     * @param TMatchInput $input
     * @return Collection<array-key, TMatchOutput>
     */
    public function __invoke($input): Collection;
}
