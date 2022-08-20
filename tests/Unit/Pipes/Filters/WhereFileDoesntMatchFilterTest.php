<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Filters;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereFileDoesntMatchFilter;
use Juampi92\Phecks\Tests\Unit\Pipes\Filters\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class WhereFileDoesntMatchFilterTest extends TestCase
{
    public function test_should_show_matching_files_using_reflection_class(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);
        $filter = new WhereFileDoesntMatchFilter('*/Filters/stubs/*');

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }

    public function test_should_show_matching_files_using_file_class(): void
    {
        $file = new FileMatch(base_path('tests/Unit/Pipes/Filters/stubs/ClassWithImports.php'));
        $filter = new WhereFileDoesntMatchFilter('*/Filters/stubs/*');

        $result = $filter($file);

        $this->assertEquals(0, $result->count());
    }

    public function test_should_reject_unmatching_files_using_reflection_class(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);
        $filter = new WhereFileDoesntMatchFilter('*/Filters2/stubs/*');

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
    }

    public function test_should_reject_unmatching_files_using_file_class(): void
    {
        $file = new FileMatch(base_path('tests/Unit/Pipes/Filters/stubs/ClassWithImports.php'));
        $filter = new WhereFileDoesntMatchFilter('*/Filters2/stubs/*');

        $result = $filter($file);

        $this->assertEquals(1, $result->count());
    }
}
