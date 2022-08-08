<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Extractor;

/**
 * @implements Extractor<class-string, string>
 */
class NamespaceExtractor implements Extractor
{
    /**
     * @param class-string $match
     * @return Collection<array-key, string>
     */
    public function extract($match): Collection
    {
        $basename = class_basename($match);

        $namespace = Str::before($match, "\\{$basename}");

        return new Collection([
            $namespace,
        ]);
    }
}
