<?php

namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class CsrfMiddleware implements MiddlewareContract {
    public function handle(Request $request, Closure $next) {
        if ($request->method === 'POST') {
            if (!isset($request->csrf_token) || $request->csrf_token !== $_SESSION['csrf_token'] ?? '') {
                http_response_code(419);
                exit('CSRF token mismatch');
            }

            regenerateCsrfToken();
        }
        return $next($request);
    }
}
