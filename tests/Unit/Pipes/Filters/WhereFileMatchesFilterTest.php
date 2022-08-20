<?php

namespace Juampi92\Phecks\Tests\Unit\Pipes\Filters;

use Juampi92\Phecks\Domain\DTOs\FileMatch;
use Juampi92\Phecks\Domain\Pipes\Filters\WhereFileMatchesFilter;
use Juampi92\Phecks\Tests\Unit\Pipes\Filters\stubs\ClassWithImports;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

class WhereFileMatchesFilterTest extends TestCase
{
    public function test_should_show_matching_files_using_reflection_class(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);
        $filter = new WhereFileMatchesFilter('*/Filters/stubs/*');

        $result = $filter($class);

        $this->assertEquals(1, $result->count());
        $this->assertEquals(ClassWithImports::class, $result->first()->getName());
    }

    public function test_should_show_matching_files_using_file_class(): void
    {
        $file = new FileMatch(base_path('tests/Unit/Pipes/Filters/stubs/ClassWithImports.php'));
        $filter = new WhereFileMatchesFilter('*/Filters/stubs/*');

        $result = $filter($file);

        $this->assertEquals(1, $result->count());
        $this->assertEquals('./tests/Unit/Pipes/Filters/stubs/ClassWithImports.php', $result->first()->file);
    }

    public function test_should_reject_unmatching_files_using_reflection_class(): void
    {
        $class = ReflectionClass::createFromName(ClassWithImports::class);
        $filter = new WhereFileMatchesFilter('*/Filters2/stubs/*');

        $result = $filter($class);

        $this->assertEquals(0, $result->count());
    }

    public function test_should_reject_unmatching_files_using_file_class(): void
    {
        $file = new FileMatch(base_path('tests/Unit/Pipes/Filters/stubs/ClassWithImports.php'));
        $filter = new WhereFileMatchesFilter('*/Filters2/stubs/*');

        $result = $filter($file);

        $this->assertEquals(0, $result->count());
    }
}
