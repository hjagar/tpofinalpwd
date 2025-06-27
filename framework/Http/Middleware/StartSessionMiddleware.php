<?php

namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class StartSessionMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        generateCsrfToken();

        return $next($request);
    }
}
