<?php

namespace Juampi92\Phecks\Domain\Violations;

use Juampi92\Phecks\Domain\DTOs\FileMatch;

class Violation
{
    private string $identifier;

    private FileMatch $file;

    private string $message;

    private ?string $url;

    /** @var ViolationSeverity::* */
    private string $severity;

    /**
     * @param ViolationSeverity::* $severity
     */
    public function __construct(string $identifier, FileMatch $file, string $message, ?string $url = null, string $severity = ViolationSeverity::ERROR)
    {
        $this->identifier = $identifier;
        $this->file = $file;
        $this->message = $message;
        $this->url = $url;
        $this->severity = $severity;
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
            '%s%s',
            $this->file->file,
            $this->file->line ? ":{$this->file->line}" : '',
        );
    }

    public function getLine(): ?int
    {
        return $this->file->line;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getFile(): FileMatch
    {
        return $this->file;
    }

    /**
     * @return ViolationSeverity::*
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }
}
