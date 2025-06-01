<?php

namespace PhpMvc\Framework\Http;
use PhpMvc\Framework\Core\Application;

// TODO: Implementar un Router para manejar las rutas y controladores
class Kernel
{
    public function __construct(private readonly Router $router) {}

    public function handle(Request $request): Response
    {
        // TODO: Implementar un Router para manejar las rutas y controladores
        $content = $this->router->dispatch($request->getUri(), $request->getMethod());
        $response = new Response($content, HttpStatus::OK->value, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Powered-By' => 'PhpMvc Framework',
        ]);

        return $response;
    }
}
