<?php

namespace Juampi92\Phecks\Domain\DTOs;

/**
 * @template TValue
 */
class MatchValue
{
    public FileMatch $file;

    /** @var TValue */
    public $value;

    /**
     * @param TValue $value
     */
    public function __construct(
        FileMatch $file,
        $value
    ) {
        $this->file = $file;
        $this->value = $value;
    }

    /**
     * @return static<FileMatch>
     */
    public static function fromFile(FileMatch $file): self
    {
        return new self($file, $file);
    }

    /**
     * @template TNewValue
     * @param TNewValue $value
     * @return static<TNewValue>
     */
    public function setValue($value): self
    {
        return new self($this->file, $value);
    }
}
