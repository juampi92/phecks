<?php

namespace Juampi92\Phecks\Domain\Pipes\Filters;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @implements Pipe<ReflectionClass, ReflectionClass>
 */
class WhereExtendsClassFilter implements Pipe
{
    /** @var class-string */
    private string $mustExtend;

    /**
     * @param  class-string  $mustExtend
     */
    public function __construct(string $mustExtend)
    {
        $this->mustExtend = $mustExtend;
    }

    /**
     * @param ReflectionClass $input
     * @return Collection<array-key, ReflectionClass>
     */
    public function __invoke($input): Collection
    {
        if (!$input->isSubclassOf($this->mustExtend)) {
            return new Collection();
        }

        return new Collection([$input]);
    }
}
