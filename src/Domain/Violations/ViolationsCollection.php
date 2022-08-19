<?php

namespace Juampi92\Phecks\Domain\Violations;

use Illuminate\Support\Collection;

/**
 * @extends Collection<array-key, Violation>
 */
class ViolationsCollection extends Collection
{
    public function whereError(): self
    {
        return $this
            ->filter(
                fn (Violation $violation): bool
                    => $violation->getSeverity() === ViolationSeverity::ERROR
            );
    }
}
