<?php
namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class AuthorizationMiddleware implements MiddlewareContract
{
    public function handle(Request $request, Closure $next)
    {
        if(!app()->getAuthorization()->check($request)) {
            header('Location: /login');
            exit;
        }

        return $next($request);
    }
}