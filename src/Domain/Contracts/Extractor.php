<?php

namespace Juampi92\Phecks\Domain\Contracts;

use Illuminate\Support\Collection;

interface Extractor
{
    public function extract(mixed $match): Collection;
}
