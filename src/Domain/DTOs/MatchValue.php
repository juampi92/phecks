<?php

namespace Juampi92\Phecks\Domain\DTOs;

/**
 * @template TValue
 * @property TValue $value
 */
class MatchValue
{
    /**
     * @param TValue $value
     */
    public function __construct(
        public FileMatch $file,
        public mixed $value,
    ) {
    }

    /**
     * @param FileMatch $file
     * @return static<FileMatch, FileMatch>
     */
    public static function fromFile(FileMatch $file): self
    {
        return new self(file: $file, value: $file);
    }

    /**
     * @template TNewValue
     * @param TNewValue $value
     * @return static<FileMatch, TNewValue>
     */
    public function setValue(mixed $value): self
    {
        return new self(file: $this->file, value: $value);
    }
}
