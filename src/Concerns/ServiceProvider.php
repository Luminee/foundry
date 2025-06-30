<?php

namespace Luminee\Foundry\Concerns;

use Luminee\Foundry\Foundry;

trait ServiceProvider
{
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
