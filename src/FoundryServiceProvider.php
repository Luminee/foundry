<?php

namespace Luminee\Foundry;

use Luminee\Foundry\Abstracts\ServiceProvider;
use Luminee\Foundry\Console\Commands\FoundryMigrateCommand;

class FoundryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerVendor(__DIR__ . '/../', 'foundry');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            FoundryMigrateCommand::class,
        ]);
    }
}
