<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\AdminController;
use App\Controllers\AppController;
use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\RegisterController;
use App\Controllers\IndexController;
use App\Controllers\MyPurchasesController;
use App\Controllers\CartController;
use App\Controllers\MenuController;
use App\Controllers\ProductController;
use App\Controllers\RoleController;
use App\Controllers\SalesController;
use App\Controllers\UserController;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Router;

// TODO: Crear el objecto Router
$router = new Router();

// Rutas del Inicio
$router->get('/', [IndexController::class, 'index'])->name('home.index');
$router->get('/product/{id}', [IndexController::class, 'show'])->name('producto.show');

// Rutas de autenticación
$router->get('/login', [AuthController::class, 'index'])->name('auth.index');
$router->post('/login', [AuthController::class, 'login'])->name('auth.login');
$router->get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rutas de registración
$router->get('/register', [RegisterController::class, 'index'])->name('register.index');
$router->post('/register', [RegisterController::class, 'store'])->name('regiter.store');

// Rutas de mis compras
$router->get('/my-purchases', [MyPurchasesController::class, 'index'])->name('my_purchases.index');
$router->get('/my-purchases/{id}/show', [MyPurchasesController::class, 'show'])->name('my_purchases.show');

// Rutas del carrito
$router->get('/cart', [CartController::class, 'index'])->name('cart.index');
$router->post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
$router->post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Rutas de contacto
$router->get('/contact', [ContactController::class, 'index'])->name('contact.index');
$router->post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Rutas de administración
$router->get('/admin', [AdminController::class, 'index'])->name('admin.index');
// Rutas de administración de menus
$router->get('/admin/menus', [MenuController::class, 'index'])->name('admin.menus.index');
$router->get('/admin/menus/create', [MenuController::class, 'create'])->name('admin.menus.create');
$router->post('/admin/menus', [MenuController::class, 'store'])->name('admin.menus.store');
$router->get('/admin/menus/{id}/edit', [MenuController::class, 'edit'])->name('admin.menus.edit');
$router->post('/admin/menus/{id}', [MenuController::class, 'update'])->name('admin.menus.update');
$router->post('/admin/menus/{id}/delete', [MenuController::class, 'destroy'])->name('admin.menus.destroy');
// Rutas de administración de roles
$router->get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
$router->get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
$router->post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
$router->get('/admin/roles/{id}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
$router->post('/admin/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
$router->post('/admin/roles/{id}/delete', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
// Rutas de administración de usuarios
$router->get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
$router->get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
$router->post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
$router->post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
$router->post('/admin/users/{id}/delete', [UserController::class, 'destroy'])->name('admin.users.destroy');
// Rutas de administración de productos
$router->get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
$router->get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
$router->post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
$router->get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
$router->post('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
$router->post('/admin/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.destroy');
// Rutas de ventas
$router->get('/admin/sales', [SalesController::class, 'index'])->name('admin.sales.index');
$router->get('/admin/sales/{id}/edit', [SalesController::class, 'edit'])->name('admin.sales.edit');
$router->post('/admin/sales/{id}', [SalesController::class, 'update'])->name('admin.sales.update');

$app = Application::configure(dirname(__DIR__), $router)
    ->withAppController(AppController::class)->build();
$app->run();
