<?php

namespace Juampi92\Phecks\Domain\Pipes\Filters;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @template TInputOutput of ReflectionClass|FileMatch
 * @implements Pipe<TInputOutput, TInputOutput>
 */
class WhereFileMatchesFilter implements Pipe
{
    /** @var array<string> */
    private $filterRegex;

    /**
     * @param  array<string>|string  $filterRegex
     */
    public function __construct($filterRegex)
    {
        $this->filterRegex = is_string($filterRegex) ? [$filterRegex] : $filterRegex;
    }

    /**
     * @param TInputOutput $input
     * @return Collection<array-key, TInputOutput>
     */
    public function __invoke($input): Collection
    {
        if ($input instanceof FileMatch) {
            return new Collection(
                $this->checkFileMatches($input->file) ? [$input] : null,
            );
        }

        if ($input instanceof ReflectionClass) {
            return new Collection(
                $this->checkFileMatches($input->getFileName()) ? [$input] : null,
            );
        }

        return new Collection([$input]);
    }

    public function checkFileMatches(string $file): bool
    {
        return collect($this->filterRegex)
            ->contains(fn (string $pattern) => Str::is($pattern, $file));
    }
}
