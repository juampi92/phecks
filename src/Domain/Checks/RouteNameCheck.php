<?php

namespace Juampi92\Phecks\Domain\Checks;

use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Sources\RouteCommandSource;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class RouteNameCheck implements Check
{
    private const ACTIONS = ['index', 'show', 'create', 'edit', 'store', 'update', 'destroy', 'redirect'];

    private const SINGULAR_SECTIONS = ['api', 'web', 'callback', 'google', 'apple', 'passport', 'adyen'];

    public function __construct(
        private RouteCommandSource $source
    ) {
    }

    public function run(): ViolationsCollection
    {
        // @to-do: remove cache first.

        return $this->source
            ->columns('name', 'uri')
            ->run()
            ->filter(fn (array $routeInfo) => $this->getNameCorrection($routeInfo['name']) !== null)
            ->mapViolations(fn (array $routeInfo) => new Violation(
                identifier: 'route_name',
                explanation: $this->getNameCorrection($routeInfo['name']),
            ));
    }

    public function getNameCorrection(string $name): ?string
    {
        $sections = explode('.', $name);

        if (count($sections) === 1) {
            // Not enough sections.
            return 'The route name has no sections. It has to have at least one. Separate sections using ".".';
        }

        $action = array_pop($sections);

        if (!in_array($action, self::ACTIONS)) {
            return "The route name has an invalid action. The action '{$action}' is not part of the valid actions.";
        }

        foreach ($sections as $section) {
            if (in_array($section, self::SINGULAR_SECTIONS)) {
                continue;
            }

            if (Str::match('/v[0-9]/', $section) !== '') {
                // Ignore v1, v2
                continue;
            }

            if (Str::match('/_|[A-Z]]|\s/', $section) !== '') {
                return "All route sections must be in kebab case. The section '{$section}' did not match this case.";
            }

            $plural = Str::plural($section);

            if ($plural !== $section) {
                return "All route sections must be in plural. The section '{$section}' is not using the plural form '{$plural}'.";
            }
        }

        return null;
    }
}
