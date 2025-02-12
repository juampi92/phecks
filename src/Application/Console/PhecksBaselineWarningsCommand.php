<?php

namespace Juampi92\Phecks\Application\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Application\Baseline\BaselineLoader;
use Juampi92\Phecks\Application\Formatters\FormatResolver;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Domain\Violations\ViolationSeverity;
use Juampi92\Phecks\Support\PathNormalizer;

class PhecksBaselineWarningsCommand extends Command
{
    /** @var string */
    protected $signature = 'phecks:warnings
                                    {--format=github : Pick a formatter.}
                                    {file*}';

    /** @var string */
    protected $description = 'Will display the baselined error inside files as warnings.';

    public function handle(BaselineLoader $baselineLoader): int
    {
        $baseline = $baselineLoader->load();

        /** @var Collection<array-key, string> $files */
        $files = collect(Arr::wrap($this->argument('file')))
            ->map(fn (string $file): string => PathNormalizer::toRelative($file));

        $violations = $files
            ->flatMap(fn (string $file): ViolationsCollection => $baseline->getViolationsForFile($file));

        $violations = new ViolationsCollection(
            $violations->map(fn (Violation $violation): Violation => $violation->setSeverity(ViolationSeverity::WARNING)),
        );

        $formatter = FormatResolver::resolve($this->option('format'), $this->input, $this->getOutput());
        $formatter->format($violations);

        // This command doesn't fail.
        return self::SUCCESS;
    }
}
