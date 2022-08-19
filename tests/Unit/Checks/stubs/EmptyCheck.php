<?php

namespace Juampi92\Phecks\Tests\Unit\Checks\stubs;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;

class EmptyCheck implements Check
{
    public function getMatches(): MatchCollection
    {
        return new MatchCollection([
            MatchValue::fromFile(new FileMatch('./test/MyMatchEmpty.php')),
        ]);
    }

    public function processMatch($match, FileMatch $file): array
    {
        return [];
    }
}
