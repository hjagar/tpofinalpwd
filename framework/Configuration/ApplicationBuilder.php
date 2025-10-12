<?php

namespace PhpMvc\Framework\Configuration;

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;
use PhpMvc\Framework\Security\AuthManager;
use PhpMvc\Framework\Security\AuthorizationInterface;

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

    /**
     * Configura el AuthorizationManager de la aplicación.
     *
     * @param string $class El nombre de la clase que implementa AuthorizationInterface.
     * @return ApplicationBuilder La instancia actual de ApplicationBuilder para encadenamiento.
     * @throws \RuntimeException Si la clase no implementa AuthorizationInterface.
     */
    public function withAuthorization(string $class): ApplicationBuilder
    {
        $this->app->setAuthorizationManager(new $class());
        return $this;
    }
}
