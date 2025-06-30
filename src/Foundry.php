<?php

namespace Luminee\Foundry;

use Illuminate\Foundation\Application;
use Luminee\Foundry\Struct\Vendor;

class Foundry
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Vendor[]
     */
    protected $vendors = [];

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string $dir
     * @param string $config_name
     * @return Vendor
     */
    public function registerVendor($dir, $config_name)
    {
        $vendor = new Vendor($dir);
        $vendor->setConfigName($config_name);

        $this->vendors[$vendor->name] = $vendor;

        return $vendor;
    }

    public function getVendor($name = null)
    {
        if ($name) {
            return $this->vendors[$name];
        }
        return $this->vendors;
    }
}
