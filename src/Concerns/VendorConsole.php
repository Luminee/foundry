<?php

namespace Luminee\Foundry\Concerns;

trait VendorConsole
{
    protected function getStubs()
    {
        $stubs_dir = $this->vendor->base_path . '/src/Console/Stubs';

        $this->vendor->console['stubs'] = $this->registerStubs($stubs_dir);
    }

    protected function registerStubs($stubs, $prefix = null)
    {
        $stubs_map = [];
        foreach (scandir($stubs) as $stub) {
            if (in_array($stub, ['.', '..'])) {
                continue;
            }
            if (is_dir($stubs . '/' . $stub)) {
                $stubs_map = array_merge(
                    $stubs_map,
                    $this->registerStubs($stubs . '/' . $stub, trim($prefix . '/' . $stub, '/'))
                );
            } else {
                $stubs_map[trim($prefix . '/' . $stub, '/')] = $stubs . '/' . $stub;
            }
        }
        return $stubs_map;
    }
}
