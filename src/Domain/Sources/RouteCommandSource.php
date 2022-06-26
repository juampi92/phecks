<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Contracts\Console\Kernel as Artisan;
use Illuminate\Support\Stringable;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\MatchString;

class RouteCommandSource
{
    /** @var array<string> */
    protected array $columns = [];

    public function __construct(
        private Artisan $artisan
    ) {
    }

    /**
     * @param array<string>|string $column
     */
    public function columns(array|string $column): self
    {
        $columns = is_array($column) ? $column : func_get_args();
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return MatchCollection<array{name: string, uri: string}>
     */
    public function run(): MatchCollection
    {
        $columns = array_merge($this->columns, ['name', 'uri']);
        $this->artisan->call('route:list', ['--columns' => $columns, '--json' => true]);

        return (new MatchString($this->artisan->output()))
            ->jsonToMatchCollection(fn (array $json) => new FileMatch(
                file: 'Route: ' . $json['uri'] . ' (name: ' . $json['name'] . ')',
            ));
    }

}
