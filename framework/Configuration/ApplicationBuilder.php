<?php

namespace PhpMvc\Framework\Configuration;

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;


class ApplicationBuilder
{
    public function __construct(private readonly Application $application) {}

    public static function configureApplication(Router $router): Application
    {
        $kernel = new Kernel($router);
        return new Application($kernel);
    }

    public function build(): Application
    {
        return $this->application;
    }

    public function withKernel(Router $router) : ApplicationBuilder {
        $this->application->setKernel(new Kernel($router));
        return $this;

    }
}
