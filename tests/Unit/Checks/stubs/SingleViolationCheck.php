<?php

namespace Juampi92\Phecks\Tests\Unit\Checks\stubs;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;

class SingleViolationCheck implements Check
{
    public function getMatches(): MatchCollection
    {
        return new MatchCollection([
            MatchValue::fromFile(new FileMatch('./test/MyMatch.php', 55)),
        ]);
    }

    public function processMatch($match, FileMatch $file): array
    {
        return [
            ViolationBuilder::make()->message('This is my error message!'),
        ];
    }
}
