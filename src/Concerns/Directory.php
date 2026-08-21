<?php

namespace Luminee\Foundry\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

trait Directory
{
    /**
     * @var Filesystem
     */
    protected $files;

    protected function initFilesystem()
    {
        $this->files = app()->make(Filesystem::class);
    }

    /**
     * Get studlied directory.
     *
     * @param $directory
     * @return array
     */
    protected function studlyDirectory($directory)
    {
        return array_map(function ($item) {
            return Str::studly($item);
        }, explode('.', $directory));
    }

    /**
     * Create the directory if not exists.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true, true);
        }

        return $path;
    }
}
