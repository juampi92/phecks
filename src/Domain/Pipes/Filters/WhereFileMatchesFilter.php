<?php

namespace Juampi92\Phecks\Domain\Pipes\Filters;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @template TInputOutput of ReflectionClass|FileMatch
 * @implements Pipe<TInputOutput, TInputOutput>
 */
class WhereFileMatchesFilter implements Pipe
{
    /** @var string */
    private string $filterRegex;

    /**
     * @param  string  $filterRegex
     */
    public function __construct(string $filterRegex)
    {
        $this->filterRegex = $filterRegex;
    }

    /**
     * @param TInputOutput $input
     * @return Collection<array-key, TInputOutput>
     */
    public function __invoke($input): Collection
    {
        if ($input instanceof FileMatch) {
            // File filter logic here.
        }
        if ($input instanceof ReflectionClass) {
            $input->getFileName();
        }

        return new Collection([$input]);
    }
}
