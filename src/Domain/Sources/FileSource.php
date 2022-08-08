<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @implements Source<FileMatch>
 */
class FileSource implements Source
{
    protected Filesystem $filesystem;

    protected ?string $dir = null;

    protected bool $recursive = false;

    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    public function directory(string $dir): self
    {
        $this->dir = $dir;

        return $this;
    }

    public function recursive(bool $recursive = true): self
    {
        $this->recursive = $recursive;

        return $this;
    }

    /**
     * @return MatchCollection<FileMatch>
     */
    public function run(): MatchCollection
    {
        if (!$this->dir) {
            throw new RuntimeException('Please specify a directory using directory(string)');
        }

        $method = $this->recursive ? 'allFiles' : 'files';

        /** @var array<SplFileInfo> */
        $files = $this->filesystem->{$method}($this->dir);

        return MatchCollection::fromFiles(
            collect($files)
                ->map(fn (SplFileInfo $fileInfo): FileMatch => new FileMatch($fileInfo->getPathname()))
                ->all(),
        );
    }
}
