<?php

namespace Luminee\Foundry\Concerns;

trait VendorConfig
{
    protected function vendorConfig()
    {
        return $this->vendor->base_path . '/config/' . $this->vendor->config_name . '.php';
    }

    protected function mergeConfig()
    {
        $config = $this->vendorConfig();

        if (file_exists(realpath($config))) {
            $this->mergeConfigFrom($config, $this->vendor->config_name);
        }

        $this->vendor->setConfig(config($this->vendor->config_name));
    }

    protected function publishConfig()
    {
        $this->publishes([
            $this->vendorConfig() => config_path($this->vendor->config_name . '.php'),
        ]);
    }
}
