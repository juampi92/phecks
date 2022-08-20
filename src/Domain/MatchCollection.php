<?php

namespace Juampi92\Phecks\Domain;

use Countable;
use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\DTOs\MatchValue;

/**
 * @template TMatch
 */
class MatchCollection implements Countable
{
    /** @var Collection<array-key, MatchValue<TMatch>> */
    private Collection $matches;

    /**
     * @param array<MatchValue<TMatch>>|Collection<array-key, MatchValue<TMatch>> $matches
     */
    public function __construct($matches = [])
    {
        $this->matches = collect($matches);
    }

    /**
     * @param array<FileMatch>|Collection<array-key, FileMatch> $files
     * @return MatchCollection<FileMatch>
     */
    public static function fromFiles($files): self
    {
        return new self(
            collect($files)
                ->map(
                    // @phpstan-ignore-next-line
                    fn (FileMatch $file) => MatchValue::fromFile($file)
                )
                ->all(),
        );
    }

    /**
     * This method is immutable.
     *
     * @template TMatchInput of TMatch
     * @template TMatchOutput
     * @param Pipe<TMatchInput, TMatchOutput>|callable(TMatchInput): Collection<array-key, TMatchOutput> $pipe
     * @return static<TMatchOutput>
     */
    public function pipe($pipe): self
    {
        return new MatchCollection(
            $this->matches
                ->flatMap(
                    fn (MatchValue $item) => $pipe($item->value)
                        ->map(fn ($value): MatchValue => $item->setValue($value)),
                )
        );
    }

    /**
     * @param callable(TMatch, FileMatch): bool|null $callable
     * @return $this<TMatch>
     */
    public function filter(callable $callable = null): self
    {
        $callable = $callable ?? (fn ($item) => (bool) $item);

        $this->matches = $this->matches->filter(fn (MatchValue $match) => $callable($match->value, $match->file));

        return $this;
    }

    /**
     * @param callable(TMatch, FileMatch): bool|null|null $callable
     * @return $this<TMatch>
     */
    public function reject(callable $callable = null): self
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
     * @return Collection<array-key, MatchValue<TMatch>>
     */
    public function getItems(): Collection
    {
        return $this->matches;
    }
}
