<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\RegisterController;
use App\Controllers\IndexController;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Router;

// TODO: Crear el objecto Router
$router = new Router();

// Rutas del Inicio
$router->get('/', [IndexController::class, 'index'])->name('root');
$router->get('/product/{id}', [IndexController::class, 'show'])->name('producto.show');

// Rutas de autenticación
$router->get('/login', [AuthController::class, 'index'])->name('login.index');
$router->post('/login', [AuthController::class, 'login'])->name('login.login');

// Rutas de registración
$router->get('/register', [RegisterController::class, 'index'])->name('register.index');
$router->post('/register', [RegisterController::class, 'store'])->name('regiter.store');

// Rutas de mis compras


// Rutas del carrito

// Rutas de contacto
$router->get('/contact', [ContactController::class, 'index'])->name('contact.index');
$router->post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Rutas de administración


$app = Application::configure(dirname(__DIR__), $router)->build();
$app->run();