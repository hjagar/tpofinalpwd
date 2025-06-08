<?php

namespace PhpMvc\Framework\View;

class View
{
    private Compiler $compiler;

    public function __construct(private string $viewsPath)
    {
        $this->compiler = new Compiler("{$viewsPath}/../storage/views");
    }

    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->$name;
        }

        return $returnValue;
    }

    public function render(string $path, array $data)
    {
        $viewFilename = "{$this->viewsPath}/{$path}.view.php";
        $compiledViewFilename = $this->compiler->compile($viewFilename);
        extract($data);
        ob_start();
        require_once $compiledViewFilename;
        return ob_get_clean();
    }
}
