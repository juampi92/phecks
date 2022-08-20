<?php

namespace Juampi92\Phecks\Domain\Sources\ValueObjects;

class ArtisanCommandOption
{
    public string $name;

    public string $shortcut;

    public string $description;

    /** @var false|mixed */
    public $default;

    /**
     * @param false|mixed $default
     */
    public function __construct(
        string $name,
        string $shortcut,
        string $description,
        $default = false
    ) {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->default = $default;
    }
}
