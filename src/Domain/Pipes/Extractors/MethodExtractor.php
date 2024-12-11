<?php

namespace Juampi92\Phecks\Domain\Pipes\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use ReflectionMethod as ReflectionMethodFilter;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;

/**
 * @implements Pipe<ReflectionClass, ReflectionMethod>
 * @phpstan-type Filter = ReflectionMethodFilter::*
 */
class MethodExtractor implements Pipe
{
    /** @var Filter|null */
    private ?int $filter;

    /**
     * @param Filter|null $filter
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
        $methods = $this->filter === null
            ? $input->getMethods()
            : $input->getMethods($this->filter);

        return collect($methods);
    }
}
