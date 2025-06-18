<?php

namespace PhpMvc\Framework\Http;

use PhpMvc\Framework\Core\Application;
use Exception;

class Kernel
{
    public function __construct(private readonly Router $router) {}

    public function handle(Request $request): Response
    {
        $content = $this->router->dispatch($request->getUri(), $request->getMethod());
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
}
