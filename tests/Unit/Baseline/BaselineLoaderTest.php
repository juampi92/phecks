<?php

namespace Juampi92\Phecks\Tests\Unit\Baseline;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Juampi92\Phecks\Application\Baseline\BaselineCollection;
use Juampi92\Phecks\Application\Baseline\BaselineLoader;
use Juampi92\Phecks\Tests\Unit\TestCase;
use Mockery\MockInterface;

class BaselineLoaderTest extends TestCase
{
    public function test_should_return_empty_baseline_collection_when_baseline_file_does_not_exist()
    {
        $config = \Mockery::mock(Repository::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('phecks.baseline')
                ->andReturn('PATH_TO_JSON_BASELINE');
        });

        $filesystem = \Mockery::mock(Filesystem::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')
                ->with('PATH_TO_JSON_BASELINE')
                ->andReturn(false);
        });

        $loader = new BaselineLoader($config, $filesystem);

        $baseline = $loader->load();

        $this->assertCount(0, $baseline->toArray());
    }

    public function test_should_return_baseline_collection_from_file()
    {
        $config = \Mockery::mock(Repository::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('phecks.baseline')
                ->andReturn('PATH_TO_JSON_BASELINE');
        });

        $filesystem = \Mockery::mock(Filesystem::class, function (MockInterface $mock) {
            $mock->shouldReceive('exists')
                ->with('PATH_TO_JSON_BASELINE')
                ->andReturn(true);

            $mock->shouldReceive('get')
                ->with('PATH_TO_JSON_BASELINE')
                ->andReturn(json_encode(['test' => 'test']));
        });

        $loader = new BaselineLoader($config, $filesystem);

        $baseline = $loader->load();

        $this->assertEquals(['test' => 'test'], $baseline->toArray());
    }

    public function test_should_save_baseline_collection()
    {
        $baseline = new BaselineCollection(['test' => 'test']);

        $config = \Mockery::mock(Repository::class, function (MockInterface $mock) {
            $mock->shouldReceive('get')
                ->with('phecks.baseline')
                ->andReturn('PATH_TO_JSON_BASELINE');
        });

        $filesystem = \Mockery::spy(Filesystem::class);

        $loader = new BaselineLoader($config, $filesystem);

        $loader->save($baseline);

        $filesystem->shouldHaveReceived('put')
            ->with('PATH_TO_JSON_BASELINE', json_encode(['test' => 'test'], JSON_PRETTY_PRINT));
    }
}
