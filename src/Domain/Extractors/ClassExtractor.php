<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use Juampi92\Phecks\Domain\DTOs\FileMatch;

class ClassExtractor implements Extractor
{
    /**
     * @param FileMatch $match
     * @return Collection<class-string>
     */
    public function extract($match): Collection
    {
        $class = $this->getClassnameFromFile($match);

        if (!$class) {
            return new Collection();
        }

        return new Collection([
            $class,
        ]);
    }

    /**
     * @return class-string|null
     */
    private function getClassnameFromFile(FileMatch $file): ?string
    {
        $fp = fopen($file->file, 'r');
        $buffer = '';
        $namespace = '';
        $i = 0;

        if (!is_resource($fp)) {
            return null;
        }

        while (true) {
            if (feof($fp)) {
                break;
            }

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (mb_strpos($buffer, '{') === false) {
                continue;
            }

            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($sub = 2; 15; $sub++) {
                        if ($tokens[$i + $sub] === ';') {
                            break;
                        }
                        $namespace .= $tokens[$i + $sub][1];
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            // We break here. @phpstan-ignore-next-line
                            return $namespace . '\\' . $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }

        return null;
    }
}
