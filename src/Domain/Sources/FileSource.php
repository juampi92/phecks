<?php

namespace Juampi92\Phecks\Domain\Sources;

use Illuminate\Filesystem\Filesystem;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;

class FileSource
{
    protected ?string $dir = null;

    protected bool $recursive = false;

    public function __construct(
        protected Filesystem $filesystem
    ) {
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
     * @return MatchCollection<MatchValue<FileMatch>>
     */
    public function run(): MatchCollection
    {
        if (!$this->dir) {
            throw new RuntimeException('Please specify a directory using directory(string)');
        }

        $method = $this->recursive ? 'allFiles' : 'files';

        return MatchCollection::fromFiles(
            collect(
                $this->filesystem->{$method}($this->dir),
            )
                ->map(fn (SplFileInfo $fileInfo): FileMatch => new FileMatch($fileInfo->getPathname()))
                ->all(),
        );
    }
}
