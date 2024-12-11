<?php

namespace Juampi92\Phecks\Domain\Pipes\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @implements Pipe<ReflectionClass, string|null>
 */
class NamespaceExtractor implements Pipe
{
    /**
     * @param ReflectionClass $input
     * @return Collection<array-key, string|null>
     */
    public function __invoke($input): Collection
    {
        return new Collection([
            $input->getNamespaceName(),
        ]);
    }
}
