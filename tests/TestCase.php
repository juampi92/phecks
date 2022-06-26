<?php

namespace Juampi92\Phecks\Tests;

use Juampi92\Phecks\PhecksServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $application
     * @return array
     */
    protected function getPackageProviders($application)
    {
        return [PhecksServiceProvider::class];
    }
}
