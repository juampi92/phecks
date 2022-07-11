<?php

namespace Juampi92\Phecks;

use Illuminate\Support\ServiceProvider;
use Juampi92\Phecks\Application\Console\PheckMakeCommand;
use Juampi92\Phecks\Application\Console\PhecksRunCommand;

class PhecksServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            return;
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../publishable/config/phecks.php',
            'phecks',
        );

        if ($this->app->runningInConsole()) {
            $this->registerPublishables();

            $this->commands([
                PhecksRunCommand::class,
                PheckMakeCommand::class,
            ]);
        }
    }

    /**
     * Registers the publishable config.
     */
    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__ . '/../publishable/config/phecks.php' => config_path('phecks.php'),
        ], 'phecks:config');
    }
}
