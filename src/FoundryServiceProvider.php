<?php

namespace Luminee\Foundry;

use Luminee\Foundry\Contracts\ServiceProvider;

class FoundryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 确保 functions.php 被加载
        $this->loadHelperFunctions();
        
        $this->registerVendor(__DIR__ . '/../', 'foundry');
    }
    
    /**
     * 加载辅助函数文件
     *
     * @return void
     */
    protected function loadHelperFunctions()
    {
        $functionsFile = __DIR__ . '/Supports/functions.php';
        
        if (file_exists($functionsFile)) {
            require_once $functionsFile;
        }
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
