<?php

namespace Juampi92\Phecks\Domain\Sources;

use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use RuntimeException;

/**
 * @implements Source<ReflectionClass>
 */
class ClassSource implements Source
{
    private BetterReflection $betterReflection;

    /** @var array<string> */
    protected array $directories = [];

    public function __construct(BetterReflection $betterReflection)
    {
        $this->betterReflection = $betterReflection;
    }

    /**
     * @param array<string>|string $dir
     * @return $this
     */
    public function directory($dir): self
    {
        if (is_string($dir)) {
            $dir = func_get_args();
        }

        $this->directories = array_merge($this->directories, $dir);

        return $this;
    }

    /**
     * @return MatchCollection<ReflectionClass>
     */
    public function run(): MatchCollection
    {
        if (count($this->directories) === 0) {
            throw new RuntimeException('Please specify a directory using ->directory(string)');
        }

        $sourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator(
                $this->directories,
                $this->betterReflection->astLocator()
            ),
            new AutoloadSourceLocator(),
        ]);
        $reflector = new DefaultReflector($sourceLocator);
        $classes = $reflector->reflectAllClasses();

        return new MatchCollection(
            collect($classes)
                ->map(fn (ReflectionClass $class): MatchValue => new MatchValue(
                    new FileMatch(
                        $class->getFileName(),
                        $class->getStartLine()
                    ),
                    $class
                ))
                ->all(),
        );
    }
}
