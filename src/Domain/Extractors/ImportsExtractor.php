<?php

namespace Juampi92\Phecks\Domain\Extractors;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\MatchCollection;
use const T_USE;

class ImportsExtractor implements Extractor
{
    /**
     * @param FileMatch $match
     * @return Collection<class-string>
     */
    public function extract($match): Collection
    {
        return $this->getImportsFromFile($match);
    }

    /**
     * @return Collection<class-string>
     */
    private function getImportsFromFile(FileMatch $file): Collection
    {
        $fp = fopen($file->file, 'r');
        $buffer = '';
        $i = 0;

        $imports = collect();

        while (true) {
            if (feof($fp)) {
                break;
            }

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (mb_strpos($buffer, 'class') === false) {
                continue;
            }

            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_USE) {
                    $importClass = '';

                    for ($sub = 2; 15; $sub++) {
                        if ($tokens[$i + $sub] === ';') {
                            break;
                        }
                        $importClass .= $tokens[$i + $sub][1];
                    }
                    $imports->push($importClass);
                }

                if ($tokens[$i][0] === T_CLASS) {
                    break 2;
                }
            }
        }

        return $imports;
    }
}
