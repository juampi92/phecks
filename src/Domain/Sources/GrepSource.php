<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use RuntimeException;
use Symfony\Component\Process\Process;

class GrepSource implements Source
{
    protected ?string $pattern = null;

    protected array $files = [];

    protected string $flags = 'RHn';

    public function __construct()
    {
    }

    public function pattern(string $pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @param array|string $files
     */
    public function files($files): self
    {
        $files = is_array($files) ? $files : func_get_args();
        $this->files = array_merge($this->files, $files);

        return $this;
    }

    public function setFlags(string $flags): self
    {
        $this->flags = $flags;

        return $this;
    }

    public function addFlags(string $flags): self
    {
        $this->flags .= $flags;

        return $this;
    }

    /**
     * @return MatchCollection<FileMatch>
     */
    public function run(): MatchCollection
    {
        $files = implode(' ', $this->files) ?: './app';

        $process = Process::fromShellCommandline("grep -{$this->flags} \"{$this->pattern}\" {$files}");
        $process->run();

        if ($process->getExitCode() > 1) {
            // 0 = Success ; 1 = No results ; > 1 => Error
            throw new RuntimeException(sprintf('Grep failed with error: %s', $process->getErrorOutput()));
        }

        return MatchCollection::fromFiles(
            Str::of($process->getOutput())->explode("\n")
                ->filter()
                ->map(function ($match): FileMatch {
                    [$file, $line, $context] = explode(':', $match, 3);

                    return new FileMatch(
                        $file,
                        (int) $line,
                        null,
                        $context,
                    );
                }),
        );
    }
}
