<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use Juampi92\Phecks\Domain\DTOs\FileMatch;

class NamespaceExtractor implements Extractor
{
    /**
     * @param string $match
     * @return Collection
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
