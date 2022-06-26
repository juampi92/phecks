<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class CheckRunner
{
    public function __construct(
        private Repository $config,
    ) {
    }

    public function run(): ViolationsCollection
    {
        return new ViolationsCollection(
            $this->getChecks()
                ->flatMap(function (Check $check) {
                    return $check->run();
                })
                ->filter(),
        );
    }

    /**
     * @return Collection<Check>
     */
    private function getChecks(): Collection
    {
        return collect($this->config->get('phecks.checks'))
            ->map(/** @param class-string<Check> $class */function ($class) {
                return resolve($class);
            });
    }
}
