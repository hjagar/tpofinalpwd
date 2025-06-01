<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpMvc\Framework\Core\ApplicationConfigure;
use PhpMvc\Framework\Http\Router;

// TODO: Crear el objecto Router
$router = new Router();
// TODO: Crear las rutas
$router->get('/', function ($req) {
    return '<h1>Hello World</h1>';
});

$app = ApplicationConfigure::configureApplication($router);
$app->run();