# Foundry

Luminee 包基础设施 —— 为基于 Foundry 体系的 Laravel 包提供统一的服务注册、配置管理与 Stub 组织能力。

## 安装

```bash
composer require luminee/foundry
```

Laravel 会自动发现 ServiceProvider，无需手动注册。

## 架构

Foundry 体系下的每个包通过继承 `Luminee\Foundry\Abstracts\ServiceProvider` 接入基础设施。该基类提供三个可选能力，均以 Trait 形式组织，按需生效：

| Trait | 能力 | 触发方式 |
|---|---|---|
| `VendorConfig` | 配置文件的合并与发布 | 调用 `mergeConfig()` / `publishConfig()` |
| `VendorConsole` | Stub 文件的自动扫描与注册 | 调用 `getStubs()` |

ServiceProvider 在 `register()` 中调用 `registerVendor()` 完成包注册，返回的 `Vendor` 对象承载包名、路径、配置等元数据，后续可在任意位置通过辅助函数或容器获取。

## 快速开始

一个典型的包 ServiceProvider 如下：

```php
<?php

namespace Luminee\YourPackage;

use Luminee\Foundry\Abstracts\ServiceProvider;

class YourPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerVendor(__DIR__ . '/../', 'your_package');

        $this->mergeConfig();
        $this->getStubs();
    }

    public function boot()
    {
        $this->publishConfig();

        $this->commands([
            Console\YourCommand::class,
        ]);
    }
}
```

### registerVendor

```php
$vendor = $this->registerVendor(__DIR__ . '/../', 'your_package');
```

- 第一个参数为包根目录（通常 `__DIR__ . '/../'`），Foundry 从中读取 `composer.json` 获取包名与版本；
- 第二个参数为配置键名，对应 `config/your_package.php`；
- 返回 `Luminee\Foundry\Struct\Vendor` 实例，同时存入 `$this->vendor` 属性；
- Vendor 实例同步绑定到容器 `foundry.vendor.<包名>`，可在包内任意位置解析。

## 获取包信息

注册后，可通过以下方式获取 Vendor 实例：

```php
// 辅助函数（推荐）
$vendor = vendor('luminee/your-package');

// 或使用 foundry()
$vendor = foundry('luminee/your-package');

// 通过容器
$vendor = app('foundry.vendor.luminee/your-package');

// 在 ServiceProvider 子类内部
$vendor = $this->getVendor();
```

Vendor 对象提供以下属性：

| 属性 | 类型 | 说明 |
|---|---|---|
| `name` | `string` | 包名，来自 composer.json |
| `config_name` | `string` | 配置键名，注册时传入 |
| `config` | `array` | 已合并的配置内容 |
| `base_path` | `string` | 包在 vendor 下的绝对路径 |
| `console` | `array` | Stub 映射表，由 `getStubs()` 填充 |
| `composer` | `object` | composer.json 的解析结果 |
| `json_file` | `string` | composer.json 的绝对路径 |

## 配置管理

在包根目录下放置 `config/your_package.php`，然后在 ServiceProvider 中：

```php
// register() — 合并到 Laravel 配置，使 config('your_package.xxx') 可用
$this->mergeConfig();

// boot() — 允许用户通过 artisan vendor:publish 发布配置
$this->publishConfig();
```

配置合并后，可通过 `package_config()` 辅助函数读取：

```php
$value = package_config('your_package', 'some_key', 'default');
```

## Stub 管理

将 Stub 文件放在 `src/Console/Stubs/` 目录下，支持子目录嵌套。在 ServiceProvider 的 `register()` 中调用 `$this->getStubs()` 即可完成扫描注册。

注册后通过辅助函数获取 Stub 路径：

```php
$path = package_stub_path('luminee/your-package', 'migration.stub');
// 子目录中的 Stub：'subdir/migration.stub'
```

## 辅助函数参考

| 函数 | 说明 |
|---|---|
| `foundry(?string $name)` | 不传参返回 Foundry 注册中心；传参返回指定 Vendor |
| `vendor(string $name)` | `foundry($name)` 的语义别名 |
| `vendor_path(string $package, string $path)` | 拼接 vendor 下包的绝对路径 |
| `package_config(string $package, ?string $key, $default)` | 读取包配置值 |
| `package_resource_path(string $vendor, string $resource)` | 获取包内资源文件路径 |
| `package_stub_path(string $vendor, string $stub)` | 获取已注册 Stub 的绝对路径 |
| `package_version(string $vendor)` | 获取包版本号 |
| `is_package_installed(string $package)` | 检查包是否已安装 |
| `get_package_info(string $vendor)` | 返回包信息数组 |

## 设计约定

1. **单一职责** — Foundry 仅处理包的注册、配置与 Stub 基础设施。数据库层能力（Model、Repository 基类等）由其他包提供，不在 Foundry 职责范围内。
2. **按需组合** — `VendorConfig` 和 `VendorConsole` 为独立 Trait，只有调用对应方法时才生效，不引入不必要的逻辑。
3. **显式优于隐式** — 包标识通过 `registerVendor()` 返回的 `Vendor` 对象获取，无需定义 `VENDOR_NAME` 常量或继承 `Foundry` 类。

## License

MIT