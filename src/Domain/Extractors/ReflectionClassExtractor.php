<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use ReflectionClass;
use ReflectionException;

class ReflectionClassExtractor implements Extractor
{
    /**
     * @param  class-string  $match
     * @return Collection<ReflectionClass>
     * @throws ReflectionException
     */
    public function extract($match): Collection
    {
        return collect([
            new ReflectionClass($match),
        ]);
    }
}
