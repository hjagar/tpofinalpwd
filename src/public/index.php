<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Kernel;
use PhpMvc\Framework\Http\Router;

// TODO: Crear el objecto Router
$router = new Router();
// TODO: Crear las rutas
$router->get('/', function ($req) {
    return '<h1>Hello World</h1>';
});
// TODO: Crear el objecto Kernel
$kernel = new Kernel($router);
// TODO: Crear el objecto Application
$app = new Application($kernel);
$app->run();