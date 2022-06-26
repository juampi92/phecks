<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Support\Stringable;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;

class MatchString extends Stringable
{
    /**
     * @param callable(array, string): FileMatch $callable
     * @return MatchCollection<MatchValue<FileMatch, array>>
     */
    public function jsonToMatchCollection(callable $callable): MatchCollection
    {
        return new MatchCollection(
            collect(
                json_decode($this->value, true),
            )
                ->map(fn (array $json, string $key) => new MatchValue($callable($json, $key), $json))
                ->all(),
        );
    }

    public function explode($delimiter = "\n", $limit = PHP_INT_MAX): MatchCollection
    {
        return MatchCollection::fromFiles(
            parent::explode($delimiter, $limit)
                ->filter()
                ->map(function ($match): FileMatch {
                    [$file, $line, $context] = explode(':', $match, 3);

                    return new FileMatch(
                        $file,
                        (int) $line,
                        $context,
                    );
                }),
        );
    }
}
