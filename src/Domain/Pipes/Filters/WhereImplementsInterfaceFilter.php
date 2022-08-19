<?php

namespace Juampi92\Phecks\Domain\Pipes\Filters;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @implements Pipe<ReflectionClass, ReflectionClass>
 */
class WhereImplementsInterfaceFilter implements Pipe
{
    /** @var class-string */
    private string $interface;

    /**
     * @param  class-string  $interface
     */
    public function __construct(string $interface)
    {
        $this->interface = $interface;
    }

    /**
     * @param ReflectionClass $input
     * @return Collection<array-key, ReflectionClass>
     */
    public function __invoke($input): Collection
    {
        if (!$input->implementsInterface($this->interface)) {
            return new Collection();
        }

        return new Collection([$input]);
    }
}
