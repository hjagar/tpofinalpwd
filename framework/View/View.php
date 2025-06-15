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

    public function render(string $view, array $data)
    {
        $viewCompiledFile = $this->compile($view);
        extract($data);
        $env = Environment::reset();
        ob_start();
        require_once $viewCompiledFile;

        if($layout = $env->getLayout()){
            $layoutCompiledFile = $this->compile($layout);
            require_once $layoutCompiledFile;
        }

        return ob_get_clean();
    }

    protected function compile($view): string
    {
        try {
            $viewFile = $this->getViewFilename($view);
            $viewCompiledFile = $this->getViewCompiledFilename($viewFile);

            if ((!file_exists($viewCompiledFile) || filemtime($viewCompiledFile) < filemtime($viewFile)) || !$this->cacheEnabled) {
                $viewContent = file_get_contents($viewFile);
                $viewCompiledContent = $this->compiler->compile($viewContent);
                file_put_contents($viewCompiledFile, $viewCompiledContent);
            }
        } catch (Exception $exc) {
            throw new Exception("La vista {$view} no encontrada", previous: $exc);
        }

        return $viewCompiledFile;
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
