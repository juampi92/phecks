<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;

class ConfigSource implements Source
{
    protected Repository $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @return MatchCollection<array{key: string, value: mixed}>
     */
    public function run(): MatchCollection
    {
        return new MatchCollection(
            collect($this->config->all())
                ->flatMap(function ($value, string $configName) {
                    $file = new FileMatch("./config/{$configName}.php");

                    return collect(Arr::dot([$configName => $value]))
                        ->map(function ($value, string $key) use ($file): MatchValue {
                            return new MatchValue($file, ['key' => $key, 'value' => $value]);
                        });
                })
                ->values(),
        );
    }
}
