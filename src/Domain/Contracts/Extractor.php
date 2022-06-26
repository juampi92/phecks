<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Illuminate\Support\Collection;

interface Extractor
{
    /**
     * @param mixed $match
     */
    public function extract($match): Collection;
}
