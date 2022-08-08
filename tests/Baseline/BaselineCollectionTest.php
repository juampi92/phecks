<?php

namespace Juampi92\Phecks\Tests\Baseline;

use Juampi92\Phecks\Application\Baseline\BaselineCollection;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Tests\TestCase;

class BaselineCollectionTest extends TestCase
{
    public function test_should_check_violation(): void
    {
        $violations = new ViolationsCollection([
            new Violation('a', new FileMatch('./app/FileOne.php'), 'lorem ipsum'),
            new Violation('b', new FileMatch('./app/FileOne.php'), 'lorem ipsum'),
            new Violation('a', new FileMatch('./app/FileTwo.php'), 'lorem ipsum'),
        ]);

        $baseline = BaselineCollection::fromViolations($violations);

        $result = $violations->reverse()->reject(fn (Violation $violation) => $baseline->checkViolation($violation));

        $this->assertEquals(0, $result->count());
    }

    public function test_should_not_check_violation_twice(): void
    {
        $violations = new ViolationsCollection([
            new Violation('a', new FileMatch('./app/FileOne.php'), 'lorem ipsum'),
            new Violation('a', new FileMatch('./app/FileOne.php'), 'lorem ipsum'),
            new Violation('b', new FileMatch('./app/FileOne.php'), 'lorem ipsum'),
            new Violation('a', new FileMatch('./app/FileTwo.php'), 'lorem ipsum'),
        ]);

        $baseline = BaselineCollection::fromViolations($violations);

        $violations->push(new Violation('a', new FileMatch('./app/FileTwo.php'), 'lorem ipsum'));

        $result = $violations->reject(fn (Violation $violation) => $baseline->checkViolation($violation));

        $this->assertEquals(1, $result->count());

        $exceeded = $baseline->getExceededViolations();
        $this->assertEquals(1, $exceeded->count());
        $this->assertEquals('./app/FileTwo.php', $exceeded->first()->getTarget());
    }
}
