<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use ReflectionClass;
use ReflectionMethod;

class ReflectionMethodExtractor implements Extractor
{
    private ?int $filter;

    /**
     * @param ReflectionMethod::*|null $filter
     */
    public function __construct(
        ?int $filter = null
    ) {
        $this->filter = $filter;
    }

    /**
     * @param ReflectionClass $match
     * @return Collection<ReflectionMethod>
     */
    public function extract($match): Collection
    {
        return collect(
            $match->getMethods($this->filter),
        );
    }
}
