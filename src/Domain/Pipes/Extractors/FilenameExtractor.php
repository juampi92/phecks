<?php

namespace Juampi92\Phecks\Domain\Pipes\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;

/**
 * @implements Pipe<FileMatch, string>
 */
class FilenameExtractor implements Pipe
{
    /**
     * @param FileMatch $input
     * @return Collection<array-key, string>
     */
    public function __invoke($input): Collection
    {
        $filename = pathinfo($input->file)['filename'];

        return new Collection([
            $filename,
        ]);
    }
}
