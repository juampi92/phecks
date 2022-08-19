<?php

namespace Juampi92\Phecks\Tests;

use Illuminate\Support\Collection;
use Juampi92\Phecks\Domain\Contracts\Pipe;
use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\MatchCollection;

class MatchCollectionTest extends TestCase
{
    public function test_it_constructs_from_files(): void
    {
        $files = collect([
            new FileMatch('./file/one.php'),
            new FileMatch('./file/two.php'),
        ]);

        $matchCollection = MatchCollection::fromFiles($files);

        $this->assertEquals(2, $matchCollection->count());
        $this->assertEquals('./file/one.php', $matchCollection->getItems()->first()->value->file);
        $this->assertEquals('./file/two.php', $matchCollection->getItems()->last()->value->file);
    }

    public function test_it_filters_and_rejects(): void
    {
        $files = collect([
            new FileMatch('./file/one.php'),
            new FileMatch('./file/two.php'),
            new FileMatch('./file/three.php'),
            new FileMatch('./file/four.php'),
        ]);

        $matchCollection = MatchCollection::fromFiles($files);

        $this->assertEquals(4, $matchCollection->count());

        $matchCollection->reject(fn (FileMatch $file) => $file->file === './file/three.php');

        $this->assertEquals(3, $matchCollection->count());

        $matchCollection->filter(fn (FileMatch $file) => $file->file === './file/two.php');

        $this->assertEquals(1, $matchCollection->count());
        $this->assertEquals('./file/two.php', $matchCollection->getItems()->first()->value->file);
    }

    public function test_extractors_can_remove_items(): void
    {
        $remover = new class() implements Pipe {
            public function __invoke($input): Collection
            {
                return collect();
            }
        };

        $files = collect([
            new FileMatch('./file/one.php'),
            new FileMatch('./file/two.php'),
            new FileMatch('./file/three.php'),
            new FileMatch('./file/four.php'),
        ]);

        $matchCollection = MatchCollection::fromFiles($files);

        $this->assertEquals(4, $matchCollection->count());

        $newMatchCollection = $matchCollection->extract($remover);

        $this->assertEquals(0, $newMatchCollection->count());

        // The original collection wasn't mutated
        $this->assertEquals(4, $matchCollection->count());
    }

    public function test_extractors_can_add_items(): void
    {
        $duplicator = new class() implements Pipe {
            public function __invoke($input): Collection
            {
                return collect([$input, $input]);
            }
        };

        $files = collect([
            new FileMatch('./file/one.php'),
            new FileMatch('./file/two.php'),
            new FileMatch('./file/three.php'),
            new FileMatch('./file/four.php'),
        ]);

        $matchCollection = MatchCollection::fromFiles($files);

        $this->assertEquals(4, $matchCollection->count());

        $newMatchCollection = $matchCollection->extract($duplicator);

        $this->assertEquals(8, $newMatchCollection->count());

        // The original collection wasn't mutated
        $this->assertEquals(4, $matchCollection->count());
    }
}
