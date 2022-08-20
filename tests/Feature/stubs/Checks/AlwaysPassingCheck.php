<?php

namespace Juampi92\Phecks\Tests\Feature\stubs\Checks;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Sources\FileSource;

/**
 * @implements Check<FileMatch>
 */
class AlwaysPassingCheck implements Check
{
    private FileSource $source;

    public function __construct(FileSource $source)
    {
        $this->source = $source;
    }

    public function getMatches(): MatchCollection
    {
        return $this->source
            ->directory('./tests/Feature/stubs/App/Console')
            ->run()
            ->pipe(
                /** @return Collection<array-key, FileMatch> */
                fn (FileMatch $item) => collect([])
            );
    }

    /**
     * @param FileMatch $match
     * @param  FileMatch  $file
     * @return array|\Juampi92\Phecks\Domain\Violations\ViolationBuilder[]
     */
    public function processMatch($match, FileMatch $file): array
    {
        return [];
    }
}
