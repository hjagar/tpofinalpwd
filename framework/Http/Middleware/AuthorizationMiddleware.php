<?php
namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class AuthorizationMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next)
    {
        if(!app()->getAuthorization()->check($request)) {
            http_response_code(403);
            echo "403 - No tienes permisos para acceder a esta página.";
            exit;
        }

        return $next($request);
    }
}