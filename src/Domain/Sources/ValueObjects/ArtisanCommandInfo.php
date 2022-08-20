<?php

namespace Juampi92\Phecks\Domain\Sources\ValueObjects;

class ArtisanCommandInfo
{
    public string $name;

    public string $description;

    /** @var array<ArtisanCommandArgument> */
    public array $arguments;

    /** @var array<ArtisanCommandOption> */
    public array $options;

    public bool $hidden;

    /**
     * @param  array<non-empty-string, ArtisanCommandArgument>  $arguments
     * @param  array<non-empty-string, ArtisanCommandOption>  $options
     */
    public function __construct(
        string $name,
        string $description,
        array $arguments,
        array $options,
        bool $hidden
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->arguments = $arguments;
        $this->options = $options;
        $this->hidden = $hidden;
    }
}
