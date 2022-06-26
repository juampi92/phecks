<?php

namespace Juampi92\Phecks\Domain\Checks;

use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\RouteCommandSource;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class RoutePathCheck implements Check
{
    private RouteCommandSource $source;

    public function __construct(
        RouteCommandSource $source
    ) {
        $this->source = $source;
    }

    public function run(): ViolationsCollection
    {
        return $this->source
            ->columns('name', 'uri')
            ->run()
            ->filter(fn (array $routeInfo) => $this->getPathCorrection($routeInfo['name']) !== null)
            ->mapViolations(fn (array $routeInfo) => new Violation(
                identifier: 'route_name',
                explanation: $this->getPathCorrection($routeInfo['name']),
            ));
    }

    public function getPathCorrection(string $path): ?string
    {
        $variables = Str::matchAll('/\{([^\?\}]+)(\?)?\}/m', $path);

        if ($variables->contains('id')) {
            return "Avoid using '{id}' on path variables. Use {resourceId} instead.";
        }

        if ($violation = $variables->first(fn ($variable) => Str::match('/-|_/', $variable) !== '')) {
            $camelCaseProposal = Str::camel($violation);

            return "Path variables should be using camelCase. Instead of {$violation}, it should be {$camelCaseProposal}.";
        }

        return null;
    }
}
