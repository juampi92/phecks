<?php

namespace Juampi92\Phecks\Domain\Violations;

use Juampi92\Phecks\Domain\DTOs\FileMatch;

class Violation
{
    private string $identifier;

    private FileMatch $file;

    private string $message;

    private ?string $tip;

    public function __construct(string $identifier, FileMatch $file, string $message, ?string $tip = null)
    {
        $this->identifier = $identifier;
        $this->file = $file;
        $this->message = $message;
        $this->tip = $tip;
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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTip(): ?string
    {
        return $this->tip;
    }

    public function getFile(): FileMatch
    {
        return $this->file;
    }
}
