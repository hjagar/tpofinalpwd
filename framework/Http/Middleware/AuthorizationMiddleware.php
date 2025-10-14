<?php
namespace PhpMvc\Framework\Http\Middleware;

use Closure;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Http\ResponseError;

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
            return ResponseError::forbidden();
        }

        return $next($request);
    }
}