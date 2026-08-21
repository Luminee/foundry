<?php

namespace Luminee\Foundry\Abstracts;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Luminee\Foundry\Concerns\VendorConfig;
use Luminee\Foundry\Concerns\VendorConsole;
use Luminee\Foundry\Foundry;
use Luminee\Foundry\Struct\Vendor;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    use VendorConfig, VendorConsole;

    /**
     * @var Vendor
     */
    protected $vendor;

    /**
     * Register the package as a vendor in Foundry.
     *
     * @param string $dir  Package root directory (usually __DIR__ . '/../')
     * @param string $config_name  Config key name
     * @return Vendor
     */
    public function registerVendor($dir, $config_name)
    {
        $this->vendor = $this->getFoundry(true)->registerVendor($dir, $config_name);

        // 绑定到容器，外部代码可通过 app('foundry.vendor.<name>') 获取
        $this->app->instance('foundry.vendor.' . $this->vendor->name, $this->vendor);

        return $this->vendor;
    }

    /**
     * Get the registered Vendor instance for this package.
     *
     * @return Vendor|null
     */
    public function getVendor()
    {
        return $this->vendor;
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
