<?php

namespace PhpMvc\Framework\Configuration;

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;

class ApplicationBuilder
{
    public function __construct(private readonly Application $application) {}

    public function build(): Application
    {
        return $this->app;
    }

    public Application $app {
        get => $this->application;
    }

    public function withKernel(Router $router): ApplicationBuilder
    {
        $this->app->setKernel(new Kernel($router));
        return $this;
    }

    public function withDotEnv(): ApplicationBuilder
    {
        $path = "{$this->app->getBasePath()}/.env";
        DotEnv::load($path);
        return $this;
    }
}
