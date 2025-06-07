<?php

namespace PhpMvc\Framework\Core;

use PhpMvc\Framework\Configuration\ApplicationBuilder;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Http\Router;

class Application
{
    public const VERSION = '1.0.0';
    public const AUTHOR = 'Gonzalo Molina';
    public const NAME = 'PHP MVC Framework';

    private Kernel $kernel;

    public function __construct(private readonly string $basePath) {}

    public function run()
    {
        $response = $this->kernel->handle(Request::createFromGlobals());
        $response->send();
    }

    public function setKernel(Kernel $kernel): void
    {
        $this->kernel = $kernel;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public static function configure(string $basePath, Router $router): ApplicationBuilder
    {
        return new ApplicationBuilder(new self($basePath))
            ->withKernel($router)
            ->withEnvironment();
    }
}
