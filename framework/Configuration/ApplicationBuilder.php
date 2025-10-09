<?php

namespace PhpMvc\Framework\Configuration;

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;
use PhpMvc\Framework\Security\AuthManager;

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

    public function withAppController($class): ApplicationBuilder
    {
        $this->app->setAppController($class);
        return $this;
    }

    public function withAuthManager($userClass): ApplicationBuilder
    {
        $this->app->setAuthManager(new AuthManager($this->app, $userClass));
        return $this;
    }

    public function withAuthorization($class): ApplicationBuilder
    {
        $this->app->setAuthorizationManager($class);
        return $this;
    }
}
