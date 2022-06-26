<?php

namespace Juampi92\Phecks\Domain\Checks;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\GrepSource;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class ActionsShouldNotExtendCheck implements Check
{
    public function __construct(
        private GrepSource $source
    ) {
    }

    public function run(): ViolationsCollection
    {
        return $this->source
            ->pattern('class [a-zA-Z]* extends [a-zA-Z]*')
            ->files('./app/Modules/*/Actions')
            ->run()
            ->mapViolations(fn () => new Violation(
                identifier: 'actions_not_extend',
                explanation: 'Actions should not extend any class',
            ));
    }
}
