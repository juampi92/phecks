<?php

namespace Juampi92\Phecks\Domain\DTOs;

/**
 * @template TValue
 * @property TValue $value
 */
class MatchValue
{
    public FileMatch $file;
    public mixed $value;

    /**
     * @param TValue $value
     */
    public function __construct(
        FileMatch $file,
        mixed $value,
    ) {
        $this->file = $file;
        $this->value = $value;
    }

    /**
     * @param FileMatch $file
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
    public function setValue(mixed $value): self
    {
        return new self($this->file, $value);
    }
}
