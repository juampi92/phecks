<?php

namespace Juampi92\Phecks\Domain\Sources;

use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Support\PathNormalizer;
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
     * @param array<string>|string $dirs
     * @return $this
     */
    public function directory($dirs): self
    {
        if (is_string($dirs)) {
            $dirs = func_get_args();
        }

        $dirs = array_map(fn (string $dir) => PathNormalizer::toAbsolute($dir), $dirs);

        $this->directories = array_merge($this->directories, $dirs);

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
