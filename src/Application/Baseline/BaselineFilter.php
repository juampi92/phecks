<?php

namespace Juampi92\Phecks\Application\Baseline;

use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class BaselineFilter
{
    public function __construct(
        private BaselineLoader $loader
    ) {
    }

    public function filter(ViolationsCollection $violations): ViolationsCollection
    {
        $baseline = $this->loader->load();

        return $violations
            ->reject(
                // Reject the violations that are expected,
                // only the amount of times they are expected.
                fn (Violation $violation) => $baseline->checkViolation($violation),
            )
            ->merge(
                // Append the violations that occurred more times than expected.
                $baseline->getExceededViolations(),
            );
    }
}
