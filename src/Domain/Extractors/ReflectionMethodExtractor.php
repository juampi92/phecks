<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use ReflectionClass;
use ReflectionMethod;

/**
 * @implements Extractor<ReflectionClass<object>, ReflectionMethod>
 */
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
     * @param ReflectionClass<object> $match
     * @return Collection<array-key, ReflectionMethod>
     */
    public function extract($match): Collection
    {
        return collect(
            $match->getMethods($this->filter),
        );
    }
}
