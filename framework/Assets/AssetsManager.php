<?php

namespace PhpMvc\Framework\Assets;

use Exception;

class AssetsManager
{
    private readonly string $assetsBasePath;
    private readonly AssetsMap $assetsMap;

    public function __construct()
    {
        $this->assetsBasePath = app()->getAssetsBasePath();
        $this->assetsMap = new AssetsMap($this->assetsBasePath);
    }

    public function __get($name)
    {
        if(property_exists($this, $name)) {
            return $this->$name;
        }
        else {
            throw new Exception("AssetManager.{$name} no se encuentra");
        }
    }

    public function render(array $assets): string
    {
        $this->verifyAssetsPath();
        return $this->assetsMap->render($assets);
    }

    private function verifyAssetsPath(): void
    {
        if (!file_exists($this->assetsBasePath)) {
            mkdir($this->assetsBasePath);
        }
    }
}
