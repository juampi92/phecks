<?php

namespace Juampi92\Phecks\Domain\Pipes\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use ReflectionMethod as ReflectionMethodFilter;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;

/**
 * @implements Pipe<ReflectionClass, ReflectionMethod>
 */
class MethodExtractor implements Pipe
{
    private ?int $filter;

    /**
     * @param ReflectionMethodFilter::*|null $filter
     */
    public function __construct(
        ?int $filter = null
    ) {
        $this->filter = $filter;
    }

    /**
     * @param ReflectionClass $input
     * @return Collection<array-key, ReflectionMethod>
     */
    public function __invoke($input): Collection
    {
        return collect(
            $input->getMethods($this->filter),
        );
    }
}
