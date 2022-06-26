<?php

namespace Juampi92\Phecks\Domain\DTOs;

class FileMatch
{
    public function __construct(
        public string $file,
        public ?int $line = null,
        public ?string $method = null,
        public ?string $context = null,
    ) {
    }
}
