<?php

namespace Juampi92\Phecks\Domain;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Extractor;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

/**
 * @template TValue
 */
class MatchCollection
{
    /** @var Collection<MatchValue<TValue>> */
    private Collection $matches;

    /**
     * @param array<MatchValue<TValue>>|Collection<MatchValue<TValue>>|null $matches
     */
    public function __construct($matches)
    {
        $this->matches = collect($matches);
    }

    /**
     * @param array<FileMatch>|Collection<FileMatch> $files
     * @return MatchCollection<FileMatch>
     */
    public static function fromFiles($files): self
    {
        return new self(
            collect($files)
                ->map(fn (FileMatch $file) => MatchValue::fromFile($file))
                ->all(),
        );
    }

    public function extract(Extractor $extractor): self
    {
        $this->matches = $this->matches
            ->flatMap(
                function (MatchValue $item) use ($extractor) {
                    return $extractor
                        ->extract($item->value)
                        ->map(fn ($value): MatchValue => $item->setValue($value));
                },
            );

        return $this;
    }

    /**
     * @param callable(TValue, FileMatch): Violation $callable
     */
    public function mapViolations(callable $callable): ViolationsCollection
    {
        return new ViolationsCollection(
            $this->matches
                ->map(fn (MatchValue $match) => $callable($match->value, $match->file))
                ->all(),
        );
    }

    /**
     * @param callable(TValue, FileMatch): bool|null $callable
     */
    public function filter($callable = null): self
    {
        $callable = $callable ?? (fn ($item) => (bool) $item);

        $this->matches = $this->matches->filter(fn (MatchValue $match) => $callable($match->value, $match->file));

        return $this;
    }

    /**
     * @param callable(TValue, FileMatch): bool|null $callable
     */
    public function reject($callable = null): self
    {
        $callable = $callable ?? (fn ($item) => (bool) $item);

        $this->matches = $this->matches->reject(fn (MatchValue $match) => $callable($match->value, $match->file));

        return $this;
    }

    public function count(): int
    {
        return $this->matches->count();
    }

    /**
     * @return Collection<MatchValue<TValue>>
     */
    public function getMatches(): Collection
    {
        return $this->matches;
    }
}
