<?php

namespace Juampi92\Phecks\Tests\Feature\stubs\Checks;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereExtendsClassFilter;
use Juampi92\Phecks\Domain\Sources\ClassSource;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;
use Roave\BetterReflection\Reflection\ReflectionClass;

/**
 * @implements Check<ReflectionClass>
 */
class ConsoleClassesMustBeSuffixedWithCommandCheck implements Check
{
    private ClassSource $source;

    public function __construct(ClassSource $source)
    {
        $this->source = $source;
    }

    public function getMatches(): MatchCollection
    {
        return $this->source
            ->directory('./tests/Feature/stubs/App/Console')
            ->run()
            ->reject(fn (ReflectionClass $class): bool => $class->isAbstract())
            ->pipe(new WhereExtendsClassFilter(Command::class));
    }

    /**
     * @param ReflectionClass $match
     * @param  FileMatch  $file
     * @return array|\Juampi92\Phecks\Domain\Violations\ViolationBuilder[]
     */
    public function processMatch($match, FileMatch $file): array
    {
        if (Str::endsWith($match->getName(), 'Command')) {
            return [];
        }

        return [
            ViolationBuilder::make()->message('Command classes must be suffixed with \'Command\''),
        ];
    }
}
