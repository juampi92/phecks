<?php

namespace Juampi92\Phecks\Domain\Violations;

use Juampi92\Phecks\Domain\DTOs\FileMatch;

class Violation
{
    public string $identifier;

    public FileMatch $file;

    protected Explanation $explanation;

    public function __construct(string $identifier, FileMatch $file, Explanation $explanation)
    {
        $this->identifier = $identifier;
        $this->file = $file;
        $this->explanation = $explanation;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getTarget(): string
    {
        return $this->file->file;
    }

    public function getLocation(): ?string
    {
        return sprintf(
            '%s%s%s',
            $this->file->file,
            $this->file->line ? ":{$this->file->line}" : '',
            $this->file->method ? " @{$this->file->method}" : '',
        );
    }

    public function getExplanation(): ?string
    {
        return $this->explanation->getText();
    }
}
