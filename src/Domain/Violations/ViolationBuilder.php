<?php

namespace Juampi92\Phecks\Domain\Violations;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use RuntimeException;

class ViolationBuilder
{
    protected ?string $identifier;

    protected ?FileMatch $file;

    protected ?string $message;

    protected ?string $url;

    protected string $severity;

    public function __construct(
        ?string $identifier = null,
        ?FileMatch $file = null,
        ?string $message = null,
        ?string $url = null
    ) {
        $this->identifier = $identifier;
        $this->file = $file;
        $this->message = $message;
        $this->url = $url;
        $this->severity = ViolationSeverity::ERROR;
    }

    public static function make(): self
    {
        return new self();
    }

    public function setFile(FileMatch $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setFileIfEmpty(FileMatch $file): self
    {
        if (!$this->file) {
            $this->setFile($file);
        }

        return $this;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function setIdentifierIfEmpty(string $identifier): self
    {
        if (!$this->identifier) {
            $this->setIdentifier($identifier);
        }

        return $this;
    }

    /**
     * @param Check<mixed> $check
     */
    public function setCheckIfEmpty(Check $check): self
    {
        $this->setIdentifierIfEmpty(class_basename($check));

        return $this;
    }

    public function message(?string $text, ?string $url = null): self
    {
        $this->message = $text;

        if (!empty($url)) {
            $this->url = $url;
        }

        return $this;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function warning(): self
    {
        $this->severity = ViolationSeverity::WARNING;

        return $this;
    }

    /**
     * @param Check<mixed> $check
     */
    public function build(Check $check, FileMatch $fileMatch): Violation
    {
        if (!$this->message) {
            throw new RuntimeException('The violation must have a message. Use ->message(string $message, ?string $tip = null) to set it.');
        }

        $this
            ->setCheckIfEmpty($check)
            ->setFileIfEmpty($fileMatch);

        return new Violation(
            $this->identifier,
            $this->file,
            $this->message,
            $this->url,
        );
    }
}
