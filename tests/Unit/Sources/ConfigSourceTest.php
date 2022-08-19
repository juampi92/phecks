<?php

namespace Juampi92\Phecks\Tests\Unit\Sources;

use Illuminate\Config\Repository;
use Juampi92\Phecks\Domain\MatchCollection;
use Juampi92\Phecks\Domain\Sources\ConfigSource;
use Juampi92\Phecks\Tests\Unit\TestCase;

class ConfigSourceTest extends TestCase
{
    public function test_it_should_work(): void
    {
        // Reset config
        $this->app['config'] = new Repository([
            'my-testing-config-key' => [
                'my_config_value' => 55,
                'my_second_config_value' => ['foo' => 'bar'],
            ],
            'other-file' => [
                'foo2' => 'bar2',
            ],
        ]);

        /** @var MatchCollection $result */
        $result = resolve(ConfigSource::class)->run();

        $this->assertCount(3, $result);

        // Assert Values
        $this->assertEquals(['key' => 'my-testing-config-key.my_config_value', 'value' => 55], $result->getItems()->first()->value);
        $this->assertEquals(['key' => 'my-testing-config-key.my_second_config_value.foo', 'value' => 'bar'], $result->getItems()->get(1)->value);
        $this->assertEquals(['key' => 'other-file.foo2', 'value' => 'bar2'], $result->getItems()->last()->value);

        // Assert Files
        $this->assertEquals('./config/my-testing-config-key.php', $result->getItems()->first()->file->file);
        $this->assertEquals('./config/other-file.php', $result->getItems()->last()->file->file);
    }
}
