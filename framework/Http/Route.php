<?php

namespace PhpMvc\Framework\Http;

use Exception;

class Route
{
    private ?string $routeName = null;
    private ?string $middlewareGroupName = null;
    private array $middlewares = [];

    public function __construct(private string $regex, private array $paramNames, private $action, private string $uri) {}

    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->$name;
        } else {
            $name = ucfirst($name);
            $method = "get{$name}";

            if (method_exists($this, $method)) {
                $returnValue = $this->$method();
            } else {
                throw new Exception("Propiedad {$name} no encontrada");
            }
        }

        return $returnValue;
    }

    public function name($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function middlewareGroup($middlewareGroupName)
    {
        $this->middlewareGroupName = $middlewareGroupName;

        return $this;
    }

    public function middleware(string $middleware) {
        $this->middlewares[] = $middleware;
  
        return $this;
    }

    public function getMiddlewareGroup() {
        return $this->middlewareGroupName;
    }

    public function getMiddlewares() {
        return $this->middlewares;
    }
}
