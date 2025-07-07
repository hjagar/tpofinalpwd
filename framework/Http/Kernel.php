<?php

namespace PhpMvc\Framework\Http;

use Closure;
use PhpMvc\Framework\Core\Application;
use Exception;
use PhpMvc\Framework\Http\Constants\HeaderType;
use PhpMvc\Framework\Http\Constants\RouteProduceType;
use PhpMvc\Framework\Http\Middleware\CsrfMiddleware;
use PhpMvc\Framework\Http\Middleware\StartSessionMiddleware;

class Kernel
{
    private array $middlewareGroups = [
        'web' => [
            StartSessionMiddleware::class,
            CsrfMiddleware::class
        ]
    ];


    public function __construct(private readonly Router $router) {}

    public function handle(Request $request): Response
    {
        $uri = $request->getUri();
        $method = $request->getMethod();
        $route = $this->router->match($uri, $method);

        if ($route) {
            $group = $route?->middlewareGroup ?? 'web';
            $middlewares = $this->middlewareGroups[$group] ?? [];
            $middlewares = array_merge($middlewares, $route->middlewares);

            $result = $this->handleMiddlewareChain($middlewares, $request, function ($request) use ($uri, $method) {
                return $this->router->dispatch($uri, $method, $request);
            });

            if ($result instanceof Response) {
                return $result;
            } else {
                $headers = [];

                if ($route->produces === RouteProduceType::JSON || is_array($result) || is_object($result)) {
                    $responseClass = JsonResponse::class;
                    $headers[HeaderType::X_CSRF_TOKEN] = csrf_token();
                } else {
                    $responseClass = Response::class;
                }

                return new $responseClass($result, HttpStatus::OK->value, $headers);
            }
        }
        else {
            return new Response('404 Not Found', HttpStatus::NOT_FOUND->value);
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

    protected function handleMiddlewareChain(array $middlewares, $request, Closure $controller)
    {
        $middlewareChain = array_reduce(
            array_reverse($middlewares),
            function ($next, $middleware) {
                return function ($request) use ($middleware, $next) {
                    $instance = new $middleware;
                    return $instance->handle($request, $next);
                };
            },
            $controller
        );

        return $middlewareChain($request);
    }
}
