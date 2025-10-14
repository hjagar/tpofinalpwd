<?php

namespace PhpMvc\Framework\Http;

use Exception;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct() {}

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("La propiedad {$name} no existe");
        }
    }

    public function get($uri, $action)
    {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        return $this->addRoute('POST', $uri, $action);
    }

    public function dispatch($uri, $method, ?Request $request = null)
    {
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH));
        $method = strtoupper($method);

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route->regex, $path, $matches)) {
                $params = [];

                foreach ($route->paramNames as $name) {
                    if (isset($matches[$name])) {
                        $params[$name] = $matches[$name];
                    }
                }

                $action = $route->action;

                // Si es POST, agrega el Request como primer parámetro
                if ($method === HttpMethods::POST->value && $request !== null) {
                    array_unshift($params, $request);
                }

                // Soporta closures y controladores
                if (is_callable($action)) {
                    return call_user_func_array($action, $params);
                } elseif (is_array($action) && count($action) === 2) {
                    [$controller, $func] = $action;
                    return call_user_func_array([new $controller, $func], $params);
                } else {
                    throw new \InvalidArgumentException('La acción no es válida.');
                }
            }
        }

        // http_response_code(404);
        // echo "404 - Ruta no encontrada";
        // return null;
        return ResponseError::notFound();
    }

    public function match(string $uri, string $method): ?Route
    {
        $returnValue = null;
        $routes = $this->routes[strtoupper($method)];
        $route = array_find($routes, fn($ro) => preg_match($ro->regex, $uri, $matches));

        if ($route !== null) {
            $returnValue = $route;
        }

        return $returnValue;
    }

    public function findRouteByName($routeName): ?Route
    {
        $returnValue = null;
        $routes = [...$this->routes['GET'], ...$this->routes['POST']];
        $route = array_find($routes, fn($ro) => $ro->routeName === $routeName);

        if ($route !== null) {
            $returnValue = $route;
        }

        return $returnValue;
    }

    public function getReactiveRoutes()
    {
        $routes = [...$this->routes['GET'], ...$this->routes['POST']];
        $reactiveRoutes = array_reduce($routes, function ($carry, $route) {
            $carry[$route->routeName] = $route->uri;
            return $carry;
        }, []);
        $jsonRoutes = json_encode($reactiveRoutes);

        return "window.ROUTES = {$jsonRoutes};";
    }

    private function addRoute($method, $uri, $action)
    {
        [$regex, $paramNames] = $this->convertToRegex($uri);
        $route = new Route($regex, $paramNames, $action, $uri);
        $this->routes[$method][] = $route;

        return $route;
    }

    private function normalize($uri)
    {
        return rtrim($uri, '/') ?: '/';
    }

    private function convertToRegex($uri)
    {
        $uri = $this->normalize($uri);
        $paramNames = [];

        // Reemplaza cada {param} con un grupo nombrado
        $pattern = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function ($matches) use (&$paramNames) {
            $paramNames[] = $matches[1];
            return '(?<' . $matches[1] . '>[^/]+)';
        }, $uri);

        return ['#^' . $pattern . '$#', $paramNames];
    }
}
