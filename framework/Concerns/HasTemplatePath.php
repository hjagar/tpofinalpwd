<?php

namespace PhpMvc\Framework\Concerns;

trait HasTemplatePath
{
    public function makeTemplateFilename(string $template, $extension = '.view.php'): string
    {
        $templatePath = str_replace('.', '/', $template);
        return "{$this->viewPath}/{$templatePath}{$extension}";
    }
}
