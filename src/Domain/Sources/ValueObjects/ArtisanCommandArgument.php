<?php

namespace Juampi92\Phecks\Domain\Sources\ValueObjects;

class ArtisanCommandArgument
{
    public string $name;

    public bool $isRequired;

    public string $description;

    public bool $isArray;

    /** @var mixed|null */
    public $default;

    /**
     * @param mixed|null $default
     */
    public function __construct(
        string $name,
        bool $isRequired,
        string $description,
        bool $isArray,
        $default = null
    ) {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->description = $description;
        $this->isArray = $isArray;
        $this->default = $default;
    }
}
