<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Contracts\Console\Kernel as Artisan;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\MatchString;

/**
 * @phpstan-type TRoute array{
 *  domain: string,
 *  method: string,
 *  uri: string,
 *  name: string,
 *  action: string,
 *  middleware: array<string>
 * }
 * @implements Source<TRoute>
 */
class RouteCommandSource implements Source
{
    private Artisan $artisan;

    /** @var array<string> */
    protected array $columns = [];

    public function __construct(
        Artisan $artisan
    ) {
        $this->artisan = $artisan;
    }

    /**
     * @return MatchCollection<TRoute>
     */
    public function run(): MatchCollection
    {
        $this->artisan->call('route:list', ['--json' => true]);

        /** @var Collection<array-key, TRoute> $routes */
        $routes = (new MatchString($this->artisan->output()))->collect();

        return new MatchCollection(
            $routes
                ->map(
                    /**
                     * @param TRoute $route
                     * @return MatchValue<TRoute>
                     */
                    function ($route): MatchValue {
                        return new MatchValue(
                            new FileMatch('Route: ' . $route['uri'] . ' (name: ' . $route['name'] . ')'),
                            $route,
                        );
                    },
                )
            ->all(),
        );
    }
}
