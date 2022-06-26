<?php

namespace Juampi92\Phecks\Application\Baseline;

use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class BaselineGenerator
{
    private BaselineLoader $loader;

    public function __construct(
        BaselineLoader $loader
    ) {
        $this->loader = $loader;
    }

    public function generate(ViolationsCollection $violations): void
    {
        $baseline = BaselineCollection::fromViolations($violations);

        $this->loader->save($baseline);
    }
}
