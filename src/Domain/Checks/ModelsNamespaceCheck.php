<?php

namespace Juampi92\Phecks\Domain\Checks;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\Extractors\NamespaceExtractor;
use Juampi92\Phecks\Domain\Sources\ClassSource;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class ModelsNamespaceCheck implements Check
{
    private ClassSource $classSource;

    public function __construct(
        ClassSource $classSource
    ) {
        $this->classSource = $classSource;
    }

    public function run(): ViolationsCollection
    {
        return $this->classSource
            ->directory('./app/Models')
            ->run()
            ->extract(resolve(NamespaceExtractor::class))
            ->reject(fn (string $namespace) => $namespace === 'App\Models')
            ->mapViolations(function (string $namespace) {
                return new Violation(
                    identifier: 'models_namespace',
                    explanation: "Model's namespace should be 'App\Models' instead of '{$namespace}'",
                );
            });
    }
}
