<?php

namespace PhpMvc\Framework\Mail;

use Exception;
use PhpMvc\Framework\Concerns\HasTemplatePath;

class TemplateCompiler
{
    use HasTemplatePath;
    private string $viewPath;

    public function __construct(private readonly string $template)
    {
        $this->viewPath = app()->getViewPath();
    }

    public function render(array $data): string
    {
        $output = $this->loadFile();
        $output = $this->processIncludes($output);

        foreach ($data as $key => $value) {
            $output = str_replace('{{ ' . $key . ' }}', $value, $output);
        }

        return $output;
    }

    private function processIncludes($templateContent) {
        $templateContent = preg_replace_callback('/\{\{\s*include\s+[\'"](.+?)[\'"]\s*\}\}/', function ($matches) {
            $includePath =  $this->makeTemplateFilename($matches[1]); // $this->basePath . '/' . $matches[1];
            if (!file_exists($includePath)) {
                throw new Exception("Included file not found: $includePath");
            }
            return file_get_contents($includePath);
        }, $templateContent);

        return $templateContent;
    }

    private function loadFile(): string
    {
        $filePath = $this->makeTemplateFilename($this->template, '.html');

        if (!file_exists($filePath)) {
            throw new Exception("Template file not found: $filePath");
        }

        $content = file_get_contents($filePath);

        return $content;
    }
}
