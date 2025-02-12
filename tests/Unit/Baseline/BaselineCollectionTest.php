<?php

namespace Juampi92\Phecks\Tests\Unit\Baseline;

use Juampi92\Phecks\Application\Baseline\BaselineCollection;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Violations\Violation;
use Juampi92\Phecks\Domain\Violations\ViolationsCollection;
use Juampi92\Phecks\Tests\Unit\TestCase;

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

    public function test_should_group_files_in_order(): void
    {
        $violations = new ViolationsCollection([
            new Violation('A', new FileMatch('./app/B.php'), 'lorem ipsum'),
            new Violation('A', new FileMatch('./app/C.php'), 'lorem ipsum'),
            new Violation('A', new FileMatch('./app/B.php'), 'lorem ipsum 2'),
            new Violation('A', new FileMatch('./app/A.php'), 'lorem ipsum'),
        ]);

        $baseline = BaselineCollection::fromViolations($violations);

        $violationFiles = array_keys($baseline->toArray()['A']);

        $this->assertEquals('./app/A.php', $violationFiles[0]);
        $this->assertEquals('./app/B.php', $violationFiles[1]);
        $this->assertEquals('./app/C.php', $violationFiles[2]);
    }

    public function test_should_get_violations_per_file(): void
    {
        // Arrange
        $filePath = './app/A.php';

        $baselineViolations = new ViolationsCollection([
            new Violation('A', new FileMatch($filePath), 'lorem ipsum'),
            new Violation('A', new FileMatch('./app/C.php'), 'lorem ipsum'),
            new Violation('B', new FileMatch($filePath), 'lorem ipsum 2'),
            new Violation('B', new FileMatch('./app/Z.php'), 'lorem ipsum'),
        ]);

        // Act
        $baseline = BaselineCollection::fromViolations($baselineViolations);
        $fileViolations = $baseline->getViolationsForFile($filePath);

        $this->assertCount(2, $fileViolations);
        $this->assertEquals('A', $fileViolations[0]->getIdentifier());
        $this->assertEquals('B', $fileViolations[1]->getIdentifier());
    }
}
