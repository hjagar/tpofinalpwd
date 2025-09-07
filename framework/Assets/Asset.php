<?php

namespace PhpMvc\Framework\Assets;

use Exception;
use PhpMvc\Framework\Assets\Constants\AssetType;
use stdClass;

class Asset
{
    private array $attributes = [
        'assetsBasePath' => '',
        'origin' => AssetType::LOCAL
    ];

    public function __construct(string $assetBasePath, stdClass $rawAsset)
    {
        $this->attributes['assetsBasePath'] = $assetBasePath;

        foreach ($rawAsset as $prop => $val) {
            if ($prop === 'properties') {
                $this->attributes[$prop] = new AssetProperties($val);
            } else {
                $this->attributes[$prop] = $val;
            }

            if ($prop === 'source' && str_starts_with($val, 'http')) {
                $this->attributes['origin'] = AssetType::REMOTE;
            }

            if ($prop === 'type' && $val === AssetType::DYNAMICJS){
                $this->attributes['origin'] = AssetType::DYNAMICJS;
            }
        }

        if ($this->attributes['origin'] === AssetType::LOCAL) {
            $this->attributes['copy'] = true;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } elseif (method_exists($this, $name)) {
            return $this->$name();
        } else {
            throw new Exception("Asset.{$name} no existe");
        }
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function render(): string
    {
        $returnValue = "";

        if ($this->isCopyable()) {
            $this->copyAsset();
        }

        $assetUrl = $this->getAssetUrl();
        $properties = "";

        if (isset($this->properties)) {
            $properties = " {$this->properties}";
        }

        $returnValue = match ($this->type) {
            AssetType::JS => "<script src=\"{$assetUrl}\" defer{$properties}></script>" . PHP_EOL,
            AssetType::DYNAMICJS => "<script src=\"{$assetUrl}\" defer{$properties}></script>" . PHP_EOL,
            AssetType::CSS => "<link href=\"{$assetUrl}\" rel=\"stylesheet\"{$properties}>" . PHP_EOL,
            default => ""
        };

        return $returnValue;
    }

    private function isCopyable(): bool
    {
        $returnValue = false;

        if ($this->copy) {
            if ($this->verifyAssetExists()) {
                $returnValue = match ($this->origin) {
                    AssetType::LOCAL => $this->verifyAssetMd5Local(),
                    AssetType::REMOTE => $this->verifyAssetMd5Remote(),
                    AssetType::DYNAMICJS => $this->verifyAssetMd5Dynamic(),
                    default => false
                };
            } else {
                $returnValue = true;
            }
        }

        return $returnValue;
    }

    private function copyAsset()
    {
        $assetPathDest = $this->getAssetPath();
        $this->verifyAssetDirectory();

        switch ($this->origin) {
            case AssetType::LOCAL:
                $frameworkPath = $this->getAssetFrameworkPath();
                copy($frameworkPath, $assetPathDest);
                break;
            case AssetType::REMOTE:
                $assetContent = file_get_contents($this->source);
                file_put_contents($assetPathDest, $assetContent);
                break;
            case AssetType::DYNAMICJS:                
                $assetContent = $this->getAssetDynamicContent();
                file_put_contents($assetPathDest, $assetContent);
                break;
        }
    }

    private function getAssetDirectory()
    {
        return "{$this->assetsBasePath}/{$this->type}";
    }

    private function verifyAssetDirectory()
    {
        $assetDirectory = $this->getAssetDirectory();

        if (!file_exists($assetDirectory)) {
            mkdir($assetDirectory);
        }
    }

    private function verifyAssetExists()
    {
        return file_exists($this->getAssetPath());
    }

    private function getAssetFrameworkPath(): string
    {
        return __DIR__ . "/{$this->source}";
    }

    private function getAssetPath()
    {
        return "{$this->getAssetDirectory()}/{$this->dest}";
    }

    private function getAssetUrl()
    {
        $returnValue = "/assets/{$this->type}/{$this->dest}";

        if ($this->origin === AssetType::REMOTE && !$this->copy) {
            $returnValue = $this->source;
        }
        
        return $returnValue;
    }

    private function verifyAssetMd5Local(): bool
    {
        $assetFramework = $this->getAssetFrameworkPath();
        $assetPath = $this->getAssetPath();

        return md5_file($assetFramework) !== md5_file($assetPath);
    }

    private function verifyAssetMd5Remote(): bool
    {
        $assetPath = $this->getAssetPath();
        $assetRemoteContent = file_get_contents($this->source);
        $assetLocalContent = file_get_contents($assetPath);

        return md5($assetRemoteContent) !== md5($assetLocalContent);
    }

    private function verifyAssetMd5Dynamic(): bool {
        $assetPath = $this->getAssetPath();
        $assetRemoteContent = $this->getAssetDynamicContent();
        $assetLocalContent = file_get_contents($assetPath);

        return md5($assetRemoteContent) !== md5($assetLocalContent);
    }

    private function getAssetDynamicContent() {
        $helper = $this->source;
        $assetContent = $helper();

        return $assetContent;
    }
}
