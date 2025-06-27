<?php

namespace PhpMvc\Framework\View;

use Exception;

class View
{
    private Compiler $compiler;
    private string $cachePath;
    private bool $cacheEnabled;

    public function __construct(private string $viewPath)
    {
        $this->cachePath = "{$viewPath}/../storage/views";
        $this->compiler = new Compiler();
        $this->cacheEnabled = env('VIEW_CACHE_ENABLED', true);
    }

    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->$name;
        }

        return $returnValue;
    }

    public function render(string $view, array $data=[])
    {
        $viewCompiledFile = $this->compile($view);
        $shared = ViewComposer::getShared();
        extract($shared + $data);
        $env = Environment::reset();
        ob_start();
        require_once $viewCompiledFile;

        if($layout = $env->getLayout()){
            $layoutCompiledFile = $this->compile($layout);
            require_once $layoutCompiledFile;
        }

        return ob_get_clean();
    }

    public function renderPartial(string $view, array $data = []) {
        $viewCompiledFile = $this->compile($view);
        $shared = ViewComposer::getShared();
        extract($shared + $data);
        include $viewCompiledFile;
    }

    public function compile($view): string
    {
        try {
            $viewCompiledMap = $this->loadViewCompiledMap();
            $viewFile = $this->getViewFilename($view);
            $viewCompiledFile = $this->getViewCompiledFilename($viewFile);
            
            if (!empty($viewCompiledMap) && isset($viewCompiledMap[$viewFile])) {
                if ($viewCompiledFile !== $viewCompiledMap[$viewFile]) {
                    unlink($viewCompiledMap[$viewFile]);
                };
            } 

            if ((!file_exists($viewCompiledFile) || filemtime($viewCompiledFile) < filemtime($viewFile)) || !$this->cacheEnabled) {
                $viewContent = file_get_contents($viewFile);
                $viewCompiledContent = $this->compiler->compile($viewContent);
                file_put_contents($viewCompiledFile, $viewCompiledContent);
                $viewCompiledMap[$viewFile] = $viewCompiledFile;
                $this->saveViewCompiledMap($viewCompiledMap);
            }
        } catch (Exception $exc) {
            throw new Exception("La vista {$view} no encontrada", previous: $exc);
        }

        return $viewCompiledFile;
    }

    private function loadViewCompiledMap() {
        $returnValue = [];
        $viewCompiledMapFile = $this->getViewCompiledMapFilename();

        if (file_exists($viewCompiledMapFile)) {
            $returnValue = json_decode(file_get_contents($viewCompiledMapFile), true);
        }

        return $returnValue;
    }

    private function saveViewCompiledMap(array $map) {
        $viewCompiledMapFile = $this->getViewCompiledMapFilename();
        file_put_contents($viewCompiledMapFile, json_encode($map));
    }

    private function getViewCompiledMapFilename(): string
    {
        return "{$this->cachePath}/view-compiled-map.json";
    }

    private function verifyCompiledFile(){

    }

    private function getViewFilename(string $view): string
    {
        $viewPath = str_replace('.', '/', $view);
        return "{$this->viewPath}/{$viewPath}.view.php";
    }

    private function getViewCompiledFilename(string $viewFile): string
    {
        $returnValue = "";

        if (file_exists($viewFile)) {
            $viewContentMd5 = md5_file($viewFile);
            $returnValue = "{$this->cachePath}/{$viewContentMd5}.php";
        } else {
            throw new Exception("Archivo {$viewFile} no encontrado");
        }

        return $returnValue;
    }
}
