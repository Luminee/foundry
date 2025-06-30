<?php

namespace Luminee\Foundry;

use Illuminate\Support\ServiceProvider;
use Luminee\Foundry\Concerns\ServiceProvider as ConcernsServiceProvider;
use Luminee\Foundry\Concerns\VendorConfig;

class FoundryServiceProvider extends ServiceProvider
{
    use ConcernsServiceProvider, VendorConfig;

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
        //
    }
}
