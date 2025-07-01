<?php

namespace Luminee\Foundry\Facades;

use Illuminate\Support\Facades\Facade;
use Luminee\Foundry\Struct\Vendor;

/**
 * Foundry Facade
 * 
 * @method static Vendor registerVendor(string $dir, string $config_name)
 * @method static Vendor getVendor(string $name = null)
 * 
 * @see \Luminee\Foundry\Foundry
 */
class Foundry extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'foundry';
    }

    /**
     * Get the Foundry instance.
     *
     * @return \Luminee\Foundry\Foundry
     */
    public static function instance()
    {
        return static::getFacadeRoot();
    }
}
