<?php

namespace Juampi92\Phecks\Domain\DTOs;

use Illuminate\Support\Str;

class FileMatch
{
    public string $file;

    public ?int $line = null;

    public ?string $context = null;

    public function __construct(
        string $file,
        ?int $line = null,
        ?string $context = null
    ) {
        $this->file = $this->fixFileRelativePath($file);
        $this->line = $line;
        $this->context = $context;
    }

    private function fixFileRelativePath(string $file): string
    {
        $file = Str::of($file);

        if (!$file->startsWith('./')) {
            $file = $file->replaceFirst(base_path(), '/')
                ->start('/')
                ->start('.');
        }

        return (string) $file;
    }
}
