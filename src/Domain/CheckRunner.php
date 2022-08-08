<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationBuilder;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationTransformer;

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
                ->flatMap(fn (Check $check) => $this->runCheck($check)),
        );
    }

    /**
     * @param Check<mixed> $check
     * @return array<Violation>
     */
    public function runCheck(Check $check): array
    {
        return $check->getMatches()
            ->getItems()
            ->flatMap(
                /** @return array<Violation> */
                fn (MatchValue $match): array => array_map(
                    fn (ViolationBuilder $builder): Violation => $builder->build($check, $match->file),
                    $check->processMatch($match->value, $match->file)
                )
            )
            ->all();
    }

    /**
     * @return Collection<array-key, Check<mixed>>
     */
    private function getChecks(): Collection
    {
        return collect($this->config->get('phecks.checks'))
            ->map(
                /** @param class-string<Check> $class */
                fn (string $class): Check => resolve($class),
            );
    }
}
