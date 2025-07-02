<?php

namespace PhpMvc\Framework\Assets;

use Exception;
use ReflectionClass;
use stdClass;

class AssetsMap
{
    private array $assets;

    public function __construct(private readonly string $assetsBasePath)
    {
        $assetsMapPath = __DIR__ . "/assets-map.json";

        if (file_exists($assetsMapPath)) {
            $rawAssetsMap = json_decode(file_get_contents($assetsMapPath));

            foreach ($rawAssetsMap as $key => $value) {
                $asset = new Asset($assetsBasePath, $value);
                $this->assets[$key] = $asset;
            }
        }
    }

    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->$name;
        } elseif (array_key_exists($name, $this->assets)) {
            $returnValue = $this->assets[$name];
        } else {
            throw new Exception("La propiedad {$name} no existe");
        }

        return $returnValue;
    }

    public function __isset($name)
    {
        return isset($this->assets[$name]);
    }

    public function loadAssetsMap(): ?object
    {
        $returnValue = null;
        $assetsMapPath = __DIR__ . "/assets-map.json";

        if (file_exists($assetsMapPath)) {
            $returnValue = json_decode(file_get_contents($assetsMapPath));
        }

        return $returnValue;
    }

    public function render($assets): string
    {
        $assetsToRender = [];

        $assets = $this->verifyAssets($assets);

        // foreach ($assets as $asset) {
        //     $assetName = $this->makeCamelCase($asset);

        //     if (isset($this->$assetName)) {
        //         $$assetsToRender[] = $this->$assetName->render();
        //     }
        // }
        foreach($assets as $asset) {
            $assetsToRender[] = $this->$asset->render();
        }

        return implode(PHP_EOL, $assetsToRender);
    }

    private function makeCamelCase(string $string): string
    {
        return preg_replace_callback(
            '/[-_](\w)/',
            fn($m) => strtoupper($m[1]),
            $string
        );
    }

    private function verifyAssets($assets): array
    {
        $returnValue = [];

        foreach ($assets as $asset) {
            $assetName = $this->makeCamelCase($asset);

            if (isset($this->$assetName)) {
                $returnValue[] = $assetName;

                if (isset($this->$assetName->includes)){
                    $returnValue = array_merge($returnValue, $this->$assetName->includes);
                }
            }
        }

        return $returnValue;
    }
}
