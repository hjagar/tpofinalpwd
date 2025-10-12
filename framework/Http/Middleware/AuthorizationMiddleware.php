<?php
namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;

class AuthorizationMiddleware implements MiddlewareContract
{
    /**
     * Maneja la autorización de la request.
     * Si el usuario no está autorizado, se devuelve un error 403.
     *
     * @param Request $request La request actual.
     * @param Closure $next La siguiente función middleware.
     * @return mixed La respuesta de la siguiente función middleware o un error 403.
     */   
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