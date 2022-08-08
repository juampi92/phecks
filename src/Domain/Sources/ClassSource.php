<?php

namespace Juampi92\Phecks\Domain\Sources;

use Juampi92\Phecks\Domain\Contracts\Source;
use Juampi92\Phecks\Domain\Extractors\ClassExtractor;
use Juampi92\Phecks\Domain\MatchCollection;
use RuntimeException;

/**
 * @implements Source<class-string>
 */
class ClassSource implements Source
{
    private FileSource $fileSource;

    protected ?string $dir = null;

    protected bool $recursive = false;

    public function __construct(FileSource $fileSource)
    {
        $this->fileSource = $fileSource;
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
     * @return MatchCollection<class-string>
     */
    public function run(): MatchCollection
    {
        if (!$this->dir) {
            throw new RuntimeException('Please specify a directory using ->directory(string)');
        }

        return $this->fileSource
            ->directory($this->dir)
            ->recursive($this->recursive)
            ->run()
            ->extract(new ClassExtractor())
            ->filter();
    }
}
