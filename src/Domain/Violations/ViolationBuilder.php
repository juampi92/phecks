<?php

namespace Juampi92\Phecks\Domain\Violations;

use Juampi92\Phecks\Domain\Contracts\Check;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use RuntimeException;

class ViolationBuilder
{
    protected ?Check $check;

    protected ?FileMatch $file;

    protected ?string $explanation;

    protected ?string $tip;

    protected ?string $identifier;

    public function __construct(
        ?Check $check = null,
        ?FileMatch $file = null,
        ?string $explanation = null,
        ?string $tip = null,
        ?string $identifier = null
    ) {
        $this->check = $check;
        $this->file = $file;
        $this->explanation = $explanation;
        $this->tip = $tip;
        $this->identifier = $identifier;
    }

    public static function make(): self
    {
        return new self();
    }

    public function file(FileMatch $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function identifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function check(Check $check): self
    {
        $this->check = $check;

        return $this;
    }

    public function explanation(?string $text, ?string $tip = null): self
    {
        $this->explanation = $text;
        $this->tip ??= $tip;

        return $this;
    }

    public function build(): Violation
    {
        if (!$this->identifier && !$this->check) {
            throw new RuntimeException('The violation\'s identifier is required. Use ->identifier(\'string\') or ->check(Check $check) to use the check\'s classname');
        }

        if (!$this->file) {
            throw new RuntimeException('The file is needed when building a violation. Use ->file(FileMatch $file) to set it.');
        }

        $identifier = $this->identifier ?: (
            $this->check ?
                class_basename($this->check)
                : null
        );

        return new Violation(
            $identifier,
            $this->file,
            new Explanation(
                $this->explanation,
                $this->tip,
            ),
        );
    }
}
