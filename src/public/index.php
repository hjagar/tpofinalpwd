<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\HelloController;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Router;

// TODO: Crear el objecto Router
$router = new Router();
// TODO: Crear las rutas
$router->get('/', function () {
    echo env('DB_CONNECTION');
    return '<h1>Hello World</h1>';
});

$router->get('/hello/{name}', function ($name) {
    return "<h1>Hello, $name!</h1>";
});

$router->get('/hola', [HelloController::class, 'index']);
$router->get('/hola/{name}', [HelloController::class, 'greet']);
$app = Application::configure(dirname(__DIR__), $router)->build();
$app->run();