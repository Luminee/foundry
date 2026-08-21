<?php

/**
 * Foundry Helper Functions
 * 
 * 提供便捷的辅助函数来简化 Foundry 包的使用
 */

if (!function_exists('foundry')) {
    /**
     * 获取 Foundry 实例
     *
     * @param string|null $vendor 可选的 vendor 名称
     * @return \Luminee\Foundry\Foundry|\Luminee\Foundry\Struct\Vendor
     */
    function foundry(?string $vendor = null)
    {
        $foundry = app('foundry');
        
        return $vendor ? $foundry->getVendor($vendor) : $foundry;
    }
}

if (!function_exists('vendor')) {
    /**
     * 获取已注册的 Vendor 实例（foundry 的别名）
     *
     * @param string $name vendor 名称，如 'luminee/belobog'
     * @return \Luminee\Foundry\Struct\Vendor|null
     */
    function vendor(string $name)
    {
        return foundry($name);
    }
}

if (!function_exists('vendor_path')) {
    /**
     * 获取指定包的路径
     *
     * @param string $package 包名 (vendor/package)
     * @param string $path 相对路径
     * @return string
     */
    function vendor_path(string $package = '', string $path = ''): string
    {
        $vendorPath = base_path('vendor');
        
        if ($package) {
            $vendorPath .= '/' . trim($package, '/');
        }
        
        if ($path) {
            $vendorPath .= '/' . ltrim($path, '/');
        }
        
        return $vendorPath;
    }
}

if (!function_exists('package_config')) {
    /**
     * 获取包配置值
     *
     * @param string $package 包名
     * @param string|null $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    function package_config(string $package, ?string $key = null, $default = null)
    {
        $configKey = $package . ($key ? '.' . $key : '');
        return config($configKey, $default);
    }
}

if (!function_exists('package_resource_path')) {
    /**
     * 获取包资源文件路径
     *
     * @param string $vendor vendor 名称
     * @param string $resource 资源相对路径
     * @return string
     */
    function package_resource_path(string $vendor, string $resource = ''): string
    {
        $foundryVendor = foundry($vendor);
        
        if (!$foundryVendor) {
            throw new \InvalidArgumentException("Vendor [{$vendor}] not found.");
        }
        
        $basePath = dirname($foundryVendor->json_file);
        
        return $resource ? $basePath . '/' . ltrim($resource, '/') : $basePath;
    }
}

if (!function_exists('package_stub_path')) {
    /**
     * 获取包 stub 文件路径
     *
     * @param string $vendor vendor 名称
     * @param string $stub stub 文件名
     * @return string|null
     */
    function package_stub_path(string $vendor, string $stub): ?string
    {
        $foundryVendor = foundry($vendor);
        
        if (!$foundryVendor || !isset($foundryVendor->console['stubs'][$stub])) {
            return null;
        }
        
        return $foundryVendor->console['stubs'][$stub];
    }
}

if (!function_exists('package_version')) {
    /**
     * 获取包版本信息
     *
     * @param string $vendor vendor 名称
     * @return string
     */
    function package_version(string $vendor): string
    {
        $foundryVendor = foundry($vendor);
        
        if (!$foundryVendor) {
            return 'unknown';
        }
        
        return $foundryVendor->composer->version ?? 'dev-master';
    }
}

if (!function_exists('is_package_installed')) {
    /**
     * 检查包是否已安装
     *
     * @param string $package 包名 (vendor/package)
     * @return bool
     */
    function is_package_installed(string $package): bool
    {
        return file_exists(vendor_path($package, 'composer.json'));
    }
}

if (!function_exists('get_package_info')) {
    /**
     * 获取包的基本信息
     *
     * @param string $vendor vendor 名称
     * @return array|null
     */
    function get_package_info(string $vendor): ?array
    {
        $foundryVendor = foundry($vendor);
        
        if (!$foundryVendor) {
            return null;
        }
        
        return [
            'name' => $foundryVendor->name,
            'version' => package_version($vendor),
            'description' => $foundryVendor->composer->description ?? '',
            'config_name' => $foundryVendor->config_name,
            'base_path' => $foundryVendor->base_path,
        ];
    }
}