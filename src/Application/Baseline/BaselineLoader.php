<?php

namespace Juampi92\Phecks\Application\Baseline;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use const JSON_PRETTY_PRINT;

class BaselineLoader
{
    public function __construct(
        private Repository $config,
        private Filesystem $filesystem,
    ) {
        // Hack needed to access files from the absolute path.
        $this->filesystem->getDriver()->getAdapter()->setPathPrefix('\\');
    }

    public function load(): BaselineCollection
    {
        $path = $this->getPath();

        if (!$this->filesystem->exists($path)) {
            return new BaselineCollection();
        }

        return new BaselineCollection(
            json_decode($this->filesystem->get($path), true),
        );
    }

    public function save(BaselineCollection $baseline): void
    {
        $path = $this->getPath();

        $this->filesystem->put(
            $path,
            json_encode($baseline->toArray(), JSON_PRETTY_PRINT),
        );
    }

    private function getPath(): string
    {
        return base_path($this->config->get('phecks.baseline'));
    }
}
