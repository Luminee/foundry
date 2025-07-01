<?php

namespace Luminee\Foundry\Contracts;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Luminee\Foundry\Concerns\VendorConfig;
use Luminee\Foundry\Concerns\VendorConsole;
use Luminee\Foundry\Foundry;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    use VendorConfig, VendorConsole;

    /**
     * @var Vendor
     */
    protected $vendor;

    public function registerVendor($dir, $config_name)
    {
        $this->vendor = $this->getFoundry(true)->registerVendor($dir, $config_name);
    }

    public function getFoundry($check = false)
    {
        if ($check && !$this->app->resolved(Foundry::class)) {
            $this->app->singleton(Foundry::class, function ($app) {
                return new Foundry($app);
            });

            // 为 Facade 注册别名
            $this->app->alias(Foundry::class, 'foundry');
        }
        return $this->app->make(Foundry::class);
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array|mixed  $commands
     * @return void
     */
    public function commands($commands)
    {
        parent::commands($commands);
    }
}
