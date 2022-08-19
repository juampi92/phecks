<?php

namespace Juampi92\Phecks\Domain\Pipes\Filters;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @implements Pipe<ReflectionClass, ReflectionClass>
 */
class WhereDoesNotExtendClassFilter implements Pipe
{
    /** @var class-string */
    private string $mustNotExtend;

    /**
     * @param  class-string  $mustNotExtend
     */
    public function __construct(string $mustNotExtend)
    {
        $this->mustNotExtend = $mustNotExtend;
    }

    /**
     * @param ReflectionClass $input
     * @return Collection<array-key, ReflectionClass>
     */
    public function __invoke($input): Collection
    {
        if ($input->isSubclassOf($this->mustNotExtend)) {
            return new Collection();
        }

        return new Collection([$input]);
    }
}
