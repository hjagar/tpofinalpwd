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
use App\Models\Usuario;
use PhpMvc\Framework\Core\Application;
use PhpMvc\Framework\Http\Constants\RouteProduceType;
use PhpMvc\Framework\Http\Middleware\AuthorizationMiddleware;
use PhpMvc\Framework\Http\Router;
use App\Security\VerifySecurity;

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
$router->get('/my-purchases', [MyPurchasesController::class, 'index'])->name('my_purchases.index')->middleware(AuthorizationMiddleware::class);
$router->get('/my-purchases/{id}/show', [MyPurchasesController::class, 'show'])->name('my_purchases.show')->middleware(AuthorizationMiddleware::class);;

// Rutas del carrito
$router->get('/cart-old', [CartController::class, 'index'])->name('cart.index-old');
$router->get('/cart', [CartController::class, 'indexAjax'])->name('cart.index')->middleware(AuthorizationMiddleware::class);;
$router->get('/cart-ssr', [CartController::class, 'indexSsr'])->name('cart.index-ssr');
$router->get('/cart-products', [CartController::class, 'cartProducts'])->name('cart.products')->produces(RouteProduceType::JSON);
$router->get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add')->produces(RouteProduceType::JSON);
$router->post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove')->produces(RouteProduceType::JSON);
$router->post('/cart/remove-one', [CartController::class, 'removeOne'])->name('cart.remove-one')->produces(RouteProduceType::JSON);
$router->post('/cart/update', [CartController::class, 'update'])->name('cart.update')->produces(RouteProduceType::JSON);
$router->post('/cart/buy', [CartController::class, 'buy'])->name('cart.buy')->produces(RouteProduceType::JSON);

// Rutas de contacto
$router->get('/contact', [ContactController::class, 'index'])->name('contact.index');
$router->post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Rutas de administración
$router->get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware(AuthorizationMiddleware::class);
// Rutas de administración de menus
$router->get('/admin/menus', [MenuController::class, 'index'])->name('admin.menus.index')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/menus/create', [MenuController::class, 'create'])->name('admin.menus.create')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/menus', [MenuController::class, 'store'])->name('admin.menus.store')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/menus/{id}/edit', [MenuController::class, 'edit'])->name('admin.menus.edit')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/menus/{id}', [MenuController::class, 'update'])->name('admin.menus.update')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/menus/{id}/delete', [MenuController::class, 'destroy'])->name('admin.menus.destroy')->middleware(AuthorizationMiddleware::class);
// Rutas de administración de roles
$router->get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/roles/{id}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/roles/{id}/delete', [RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware(AuthorizationMiddleware::class);
// Rutas de administración de usuarios
$router->get('/admin/users', [UserController::class, 'index'])->name('admin.users.index')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/users', [UserController::class, 'store'])->name('admin.users.store')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/users/{id}/delete', [UserController::class, 'destroy'])->name('admin.users.destroy')->middleware(AuthorizationMiddleware::class);
// Rutas de administración de productos
$router->get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/products/{id}/delete', [ProductController::class, 'destroy'])->name('admin.products.destroy')->middleware(AuthorizationMiddleware::class);
// Rutas de ventas
$router->get('/admin/sales', [SalesController::class, 'index'])->name('admin.sales.index')->middleware(AuthorizationMiddleware::class);
$router->get('/admin/sales/{id}/edit', [SalesController::class, 'edit'])->name('admin.sales.edit')->middleware(AuthorizationMiddleware::class);
$router->post('/admin/sales/{id}', [SalesController::class, 'update'])->name('admin.sales.update')->middleware(AuthorizationMiddleware::class);

$app = Application::configure(dirname(__DIR__), $router)
    ->withAppController(AppController::class)
    ->withAuthManager(Usuario::class)
    ->withAuthorization(VerifySecurity::class)
    ->build();
$app->run();
