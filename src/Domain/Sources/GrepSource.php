<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\MatchString;
use Symfony\Component\Process\Process;

class GrepSource
{
    protected ?string $pattern = null;

    protected ?array $files = [];

    protected string $flags = 'RHn';

    public function __construct()
    {
    }

    public function pattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function files(array|string $files): self
    {
        $files = is_array($files) ? $files : func_get_args();
        $this->files = array_merge($this->files, $files);

        return $this;
    }

    /**
     * @return MatchCollection<MatchValue<FileMatch>>
     */
    public function run(): MatchCollection
    {
        $files = implode(' ', $this->files) ?: './app';

        $process = Process::fromShellCommandline("grep -{$this->flags} \"{$this->pattern}\" {$files}");
        $process->run();

        return MatchCollection::fromFiles(
            Str::of($process->getOutput())->explode("\n")
                ->filter()
                ->map(function ($match): FileMatch {
                    [$file, $line, $context] = explode(':', $match, 3);

                    return new FileMatch(
                        file: $file,
                        line: (int) $line,
                        context: $context,
                    );
                }),
        );
    }
}
