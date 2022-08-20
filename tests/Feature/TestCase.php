<?php

namespace Juampi92\Phecks\Tests\Feature;

use Juampi92\Phecks\PhecksServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(
            realpath(__DIR__.'/../..') ?: __DIR__
        );
    }

    /**
     * @param \Illuminate\Foundation\Application $application
     * @return array
     */
    protected function getPackageProviders($application)
    {
        return [PhecksServiceProvider::class];
    }
}
