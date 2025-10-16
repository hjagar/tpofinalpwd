<?php

namespace PhpMvc\Framework\Core;

use Exception;
use PhpMvc\Framework\Configuration\ApplicationBuilder;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Http\Router;
use PhpMvc\Framework\Security\AuthManager;
use PhpMvc\Framework\Security\AuthorizationInterface;

class Application
{
    public const VERSION = '1.0.0';
    public const AUTHOR = 'Gonzalo Molina';
    public const NAME = 'PHP MVC Framework';

    private static Application $instance;

    private Kernel $kernel;
    private string $viewPath;
    private string $compiledViewPath;
    private $appController = null;
    private ?AuthorizationInterface $authorizationManager = null;
    private ?AuthManager $authManager = null;

    public function __construct(private readonly string $basePath)
    {
        $this->viewPath = "{$basePath}/Views";
        $this->compiledViewPath = "{$this->viewPath}/compiled";
        static::$instance = $this;
    }

    private function boot()
    {
        if ($this->appController !== null) {
            $this->appController->boot();
        }
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("La propiedad {$name} no existe");
        }
    }

    public function run()
    {
        $this->boot();
        $response = $this->kernel->handle(Request::createFromGlobals());
        $response->send();
    }

    public function setKernel(Kernel $kernel): void
    {
        $this->kernel = $kernel;
    }

    public function setAppController($appControllerClass)
    {
        if (class_exists($appControllerClass)) {
            $this->appController = new $appControllerClass();
        }
    }

    public function setAuthManager($authManager)
    {
        $this->authManager = $authManager;
    }

    public function setAuthorizationManager(AuthorizationInterface $authorizationManager)
    {
        $this->authorizationManager = $authorizationManager;
    }


    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    public function getAssetsBasePath(): string
    {
        return "{$this->basePath}/public/assets";
    }

    public function getCompiledViewPath(): string
    {
        return $this->compiledViewPath;
    }

    public static function configure(string $basePath, Router $router): ApplicationBuilder
    {
        return new ApplicationBuilder(new self($basePath))
            ->withKernel($router)
            ->withDotEnv();
    }

    public static function getInstance(): Application
    {
        return static::$instance;
    }

    public function getRoute($routeName, $parameters = [])
    {
        $route = $this->kernel->router->findRouteByName($routeName);
        $paramNames = $route?->paramNames ?? [];
        $namedParameters = array_combine($paramNames, $parameters);
        $routeTemplate = $this->regexToTemplate($route?->regex ?? '');
        $routeUrl = $this->fillTemplate($routeTemplate, $namedParameters) ?? '';

        return $routeUrl;
    }

    public function getAuth()
    {
        if ($this->authManager === null) {
            throw new Exception('Authentication service is not available.');
        }

        return $this->authManager;
    }

    public function getAuthorization()
    {
        if ($this->authorizationManager === null) {
            throw new Exception('Authorization service is not available.');
        }

        return $this->authorizationManager;
    }

    public function getReactiveRoutes()
    {
        return $this->kernel->router->getReactiveRoutes();
    }

    private function regexToTemplate(string $regex): string
    {
        $pattern = trim($regex, "#^$");
        $pattern = preg_replace_callback('/\(\?<(?<name>\w+)>[^)]+\)/', function ($match) {
            return '{' . $match['name'] . '}';
        }, $pattern);

        return $pattern;
    }

    private function fillTemplate(string $template, array $params): string
    {
        foreach ($params as $key => $value) {
            $template = str_replace("{" . $key . "}", urlencode($value), $template);
        }

        return $template;
    }
}
