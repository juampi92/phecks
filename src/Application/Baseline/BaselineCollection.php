<?php

namespace Juampi92\Phecks\Application\Baseline;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Explanation;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;

class BaselineCollection
{
    /** @var Collection<string, array<string, int>> */
    private Collection $baseline;

    public function __construct(array $baseline = [])
    {
        $this->baseline = collect($baseline);
    }

    public function checkViolation(Violation $violation): bool
    {
        $group = collect(
            $this->baseline->get($violation->getIdentifier()),
        );

        $count = $group->get($violation->getTarget(), null);

        if (is_null($count)) {
            return false;
        }

        // We register that there are more violations
        // than what the baseline recorded.
        $this->baseline->put(
            $violation->getIdentifier(),
            $group->put($violation->getTarget(), $count - 1)->all(),
        );

        if ($count > 0) {
            // If the counter run out, we don't filter out this violation.
            return true;
        }

        return false;
    }

    /**
     * Get Violations that ocurred more times than what the baseline recorded.
     * @return Collection<Violation>
     */
    public function getExceededViolations(): Collection
    {
        return $this->baseline
            ->flatMap(function (array $violations, string $identifier): Collection {
                return collect($violations)
                    ->filter(fn (int $counter) => $counter < 0)
                    ->map(function (int $counter, string $target) use ($identifier): Violation {
                        $extraOccurrences = abs($counter);

                        return new Violation(
                            $identifier,
                            new FileMatch($target),
                            "Ignored check ocurred {$extraOccurrences} more times than expected.",
                        );
                    });
            })
            ->values();
    }

    public static function fromViolations(ViolationsCollection $collection): self
    {
        return new self(
            $collection
                ->groupBy(fn (Violation $violation): string => $violation->getIdentifier())
                ->map(
                    fn (Collection $violations): array => $violations
                        ->groupBy(fn (Violation $violation) => $violation->getTarget())
                        ->map->count()
                        ->all(),
                )
                ->all(),
        );
    }

    public function toArray(): array
    {
        return $this->baseline->toArray();
    }
}
