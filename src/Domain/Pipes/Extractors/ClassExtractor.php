<?php

namespace Juampi92\Phecks\Domain\Pipes\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

/**
 * @implements Pipe<FileMatch, ReflectionClass>
 */
class ClassExtractor implements Pipe
{
    /**
     * @param FileMatch $input
     * @return Collection<array-key, ReflectionClass>
     */
    public function __invoke($input): Collection
    {
        $astLocator = (new BetterReflection())->astLocator();
        $sourceLocator = new AggregateSourceLocator([
            new SingleFileSourceLocator($input->file, $astLocator),
            new AutoloadSourceLocator(),
        ]);
        $reflector = new DefaultReflector($sourceLocator);
        $classes = $reflector->reflectAllClasses();

        return new Collection($classes);
    }
}
