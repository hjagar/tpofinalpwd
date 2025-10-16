<?php

namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class AuthMiddleware implements MiddlewareContract {
    public function handle(Request $request, Closure $next) {
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect'] = $request->uri;
            header('Location: /login');
            exit;
        }
        return $next($request);
    }
}
