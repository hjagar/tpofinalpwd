<?php

namespace PhpMvc\Framework\Http;

use Closure;
use PhpMvc\Framework\Core\Application;
use Exception;
use PhpMvc\Framework\Http\Middleware\CsrfMiddleware;
use PhpMvc\Framework\Http\Middleware\StartSessionMiddleware;

class Kernel
{
    private array $middlewareGroups = [
        'web' => [
            StartSessionMiddleware::class,
            CsrfMiddleware::class,
            // AuthMiddleware::class,
        ]
    ];


    public function __construct(private readonly Router $router) {}

    public function handle(Request $request): Response
    {
        $uri = $request->getUri();
        $method = $request->getMethod();

        $route = $this->router->match($uri, $method);
        $group = $route?->middlewareGroup ?? 'web';
        $middlewares = $this->middlewareGroups[$group] ?? [];
        $middlewares = array_merge($middlewares, $route->middlewares);

        $content = $this->handleMiddlewareChain($middlewares, $request, function ($request) use ($uri, $method) {
            return $this->router->dispatch($uri, $method, $request);
        });

        //$content = $this->router->dispatch($request->getUri(), $request->getMethod());
        $response = new Response($content, HttpStatus::OK->value, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Powered-By' => Application::NAME,
            'X-Version' => Application::VERSION,
            'X-Author' => Application::AUTHOR,
        ]);

        return $response;
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
