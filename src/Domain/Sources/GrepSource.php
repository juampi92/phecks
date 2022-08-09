<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Support\Str;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Sources\Flags\GrepFlags;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * @implements Source<FileMatch>
 */
class GrepSource implements Source
{
    protected ?string $pattern = null;

    protected array $files = [];

    /** @var array<string> */
    protected array $flags = [
        GrepFlags::DEFERENCE_RECURSIVE,
        GrepFlags::WITH_FILENAME,
        GrepFlags::LINE_NUMBER,
    ];

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

    /**
     * @param string|array<string> $flags
     */
    public function setFlags($flags): self
    {
        if (!is_array($flags)) {
            $flags = func_get_args();
        }

        $this->flags = $flags;

        return $this;
    }

    /**
     * @param string|array<string> $flags
     */
    public function addFlags($flags): self
    {
        if (!is_array($flags)) {
            $flags = func_get_args();
        }

        $this->flags = array_merge($this->flags, $flags);

        return $this;
    }

    /**
     * @return MatchCollection<FileMatch>
     */
    public function run(): MatchCollection
    {
        $files = implode(' ', $this->files) ?: './app';
        $flags = implode(' ', $this->flags);

        $process = Process::fromShellCommandline("grep {$flags} \"{$this->pattern}\" {$files}");
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
                        $context,
                    );
                }),
        );
    }
}
