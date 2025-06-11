<?php

namespace PhpMvc\Framework\View;

class Environment
{
    private static $instance;
    private $sections = [];
    private $sectionStack = [];
    private $layout;

    public static function getInstance(): self
    {
        return self::$instance ??= new static();
    }

    public static function reset(): self
    {
        self::$instance = new static();
        return self::$instance;
    }

    public function section(string $name): void
    {
        ob_start();
        $this->sectionStack[] = $name;
    }

    public function endSection(): void
    {
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = trim(ob_get_clean());
    }

    public function yield(string $name): string
    {
        return $this->sections[$name] ?? '';
    }

    public function extends(string $view): void
    {
        $this->layout = $view;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }
}