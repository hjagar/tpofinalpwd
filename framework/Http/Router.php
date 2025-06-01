<?php

namespace PhpMvc\Framework\Http;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct() {}

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action)
    {
        [$regex, $paramNames] = $this->convertToRegex($uri);
        $this->routes[$method][] = [
            'regex' => $regex,
            'paramNames' => $paramNames,
            'action' => $action,
            'original' => $uri,
        ];
    }

    public function dispatch($uri, $method, ?Request $request = null)
    {
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH));
        $method = strtoupper($method);

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['regex'], $path, $matches)) {
                $params = [];
                foreach ($route['paramNames'] as $name) {
                    if (isset($matches[$name])) {
                        $params[$name] = $matches[$name];
                    }
                }

                [$controller, $func] = $route['action'];

                // Si es POST, agrega el Request como primer parámetro
                if ($method === 'POST' && $request !== null) {
                    array_unshift($params, $request);
                }

                return call_user_func_array([new $controller, $func], $params);
            }
        }

        http_response_code(404);
        echo "404 - Ruta no encontrada";
        return null;
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
