<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\DTOs\FileMatch;

class MatchString
{
    protected string $value;

    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    public function collect(): Collection
    {
        return collect(
            json_decode($this->value, true),
        );
    }

    /**
     * @param non-empty-string $delimiter
     * @param int $limit
     * @return MatchCollection<FileMatch>
     */
    public function explode($delimiter = "\n", $limit = PHP_INT_MAX)
    {
        return MatchCollection::fromFiles(
            collect(explode($delimiter, $this->value, $limit))
                ->filter()
                ->map(function ($match): FileMatch {
                    [$file, $line, $context] = explode(':', $match, 3);

                    return new FileMatch(
                        $file,
                        (int) $line,
                        null,
                        $context,
                    );
                }),
        );
    }
}
