<?php

namespace Juampi92\Phecks\Tests\Feature\Sources;

use Illuminate\Support\Facades\Route;
use Juampi92\Phecks\Domain\Sources\RouteCommandSource;
use Juampi92\Phecks\Domain\Sources\ValueObjects\RouteInfo;
use Juampi92\Phecks\Tests\Feature\TestCase;

class RouteCommandSourceTest extends TestCase
{
    public function test_should_work(): void
    {
        // Arrange
        Route::get('/example-route-123', function (int $a) {
            return $a;
        })
            ->name('my_route');
        Route::post('/example-route-no-name', function (int $a) {
            return $a;
        });

        /** @var RouteCommandSource $source */
        $source = resolve(RouteCommandSource::class);

        // Act
        $output = $source->run();

        // Assert
        $routesInfo = $output->getItems()->map->value;

        $this->assertInstanceOf(RouteInfo::class, $routesInfo[0]);
        $this->assertEquals('my_route', $routesInfo[0]->name);
        $this->assertNull($routesInfo[1]->name);
    }
}
