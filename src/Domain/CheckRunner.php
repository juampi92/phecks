<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class CheckRunner
{
    private Repository $config;

    public function __construct(
        Repository $config
    ) {
        $this->config = $config;
    }

    public function run(): ViolationsCollection
    {
        return new ViolationsCollection(
            $this->getChecks()
                ->flatMap(function (Check $check): Collection {
                    return $check->run();
                }),
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
