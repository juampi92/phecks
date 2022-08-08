<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use ReflectionClass;
use ReflectionException;

/**
 * @template TClass of object
 * @implements Extractor<class-string<TClass>, ReflectionClass<TClass>>
 */
class ReflectionClassExtractor implements Extractor
{
    /**
     * @param  class-string<TClass>  $match
     * @return Collection<array-key, ReflectionClass<TClass>>
     * @throws ReflectionException
     */
    public function extract($match): Collection
    {
        return collect([
            new ReflectionClass($match),
        ]);
    }
}
