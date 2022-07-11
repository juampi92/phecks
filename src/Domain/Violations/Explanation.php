<?php

namespace Juampi92\Phecks\Domain\Violations;

class Explanation
{
    protected ?string $text;

    protected ?string $tip;

    public function __construct(?string $text = null, ?string $tip = null)
    {
        $this->text = $text;
        $this->tip = $tip;
    }

    public static function empty(): self
    {
        return new self();
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getTip(): ?string
    {
        return $this->tip;
    }
}
