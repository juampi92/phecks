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
 * @template TRoute of array{name: string, uri: string}
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
     * @param array<string>|string $column
     * @return $this<TRoute>
     */
    public function columns($column): self
    {
        $columns = is_array($column) ? $column : func_get_args();
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return MatchCollection<TRoute>
     */
    public function run(): MatchCollection
    {
        $columns = array_merge($this->columns, ['name', 'uri']);
        $this->artisan->call('route:list', ['--columns' => $columns, '--json' => true]);

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
