<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Extractor;

class NamespaceExtractor implements Extractor
{
    /**
     * @param string $match
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
