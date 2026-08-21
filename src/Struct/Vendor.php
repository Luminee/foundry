<?php

namespace Luminee\Foundry\Struct;

class Vendor
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $config_name;

    /**
     * @var array
     */
    public $config;

    /**
     * @var string
     */
    public $base_path;

    /**
     * @var \stdClass
     */
    public $composer;

    /**
     * @var string
     */
    public $json_file;

    /**
     * @var array
     */
    public $console;

    public function __construct($dir)
    {
        $this->json_file = realpath($dir . '/composer.json');
        $this->composer = json_decode(file_get_contents($this->json_file));

        $this->name = $this->composer->name;

        $this->base_path = base_path('vendor/' . $this->name);
    }

    public function setConfigName($name)
    {
        $this->config_name = $name;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'config_name' => $this->config_name,
            'config' => $this->config,
            'console' => $this->console,
            'base_path' => $this->base_path,
            'composer' => $this->composer,
            'json_file' => $this->json_file,
        ];
    }
}
