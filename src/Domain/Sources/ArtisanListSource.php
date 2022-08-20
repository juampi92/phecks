<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Contracts\Console\Kernel as Artisan;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\MatchString;
use Juampi92\Phecks\Domain\Sources\ValueObjects\ArtisanCommandArgument;
use Juampi92\Phecks\Domain\Sources\ValueObjects\ArtisanCommandInfo;
use Juampi92\Phecks\Domain\Sources\ValueObjects\ArtisanCommandOption;

/**
 * @implements Source<ArtisanCommandInfo>
 */
class ArtisanListSource implements Source
{
    private Artisan $artisan;

    private bool $showingHidden = false;

    public function __construct(Artisan $artisan)
    {
        $this->artisan = $artisan;
    }

    /**
     * @return $this<ArtisanCommandInfo>
     */
    public function showHidden(): self
    {
        $this->showingHidden = true;

        return $this;
    }

    public function run(): MatchCollection
    {
        $this->artisan->call('list', ['--format' => 'json']);

        return new MatchCollection(
            collect(
                (new MatchString($this->artisan->output()))
                    ->collect()
                    ->get('commands')
            )
                ->when(!$this->showingHidden, function (Collection $commands): Collection {
                    return $commands->reject(fn ($command) => $command['hidden'] === true);
                })
                ->map(function (array $commandStructure): MatchValue {
                    return new MatchValue(
                        new FileMatch("Command: {$commandStructure['name']}", 1),
                        new ArtisanCommandInfo(
                            $commandStructure['name'],
                            $commandStructure['description'],
                            collect($commandStructure['definition']['arguments'])
                                ->map(function (array $argumentStructure): ArtisanCommandArgument {
                                    return new ArtisanCommandArgument(
                                        $argumentStructure['name'],
                                        $argumentStructure['is_required'],
                                        $argumentStructure['description'],
                                        $argumentStructure['is_array'],
                                        $argumentStructure['default']
                                    );
                                })
                                ->all(),
                            collect($commandStructure['definition']['options'])
                                ->map(function (array $optionStructure): ArtisanCommandOption {
                                    return new ArtisanCommandOption(
                                        $optionStructure['name'],
                                        $optionStructure['shortcut'],
                                        $optionStructure['description'],
                                        $optionStructure['default']
                                    );
                                })
                                ->all(),
                            $commandStructure['hidden']
                        )
                    );
                })
        );
    }
}
