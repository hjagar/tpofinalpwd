<?php

namespace App\Security;

use App\Models\Usuario;
use PhpMvc\Framework\Security\AuthorizationInterface;
use PhpMvc\Framework\Http\Request;

class AuthorizationManager implements AuthorizationInterface
{
    /**
     * Comprueba si el usuario tiene acceso a una ruta en particular.
     * La funcion utiliza una expresion regular para extraer la ruta base de la request
     * y luego consulta a la base de datos para verificar si el usuario tiene permiso
     * para acceder a esa ruta.
     *
     * @param Request $request La request actual.
     * @return bool True si el usuario tiene acceso, false en caso contrario.
     */
    public function check(Request $request): bool
    {
        $idusuario = user()?->idusuario;
        $regex = '/^(?<BaseRoute>\/[^\/]+(?:\/[^\/]+)?)/m';
        $requestUri = $request->getUri();
        preg_match_all($regex, $requestUri, $matches, PREG_SET_ORDER, 0);
        $matchedUri = $matches[0]['BaseRoute'];
        $hasAccessResult = Usuario::rawQueryOne('sqlAuthorizationCheck', [$idusuario, $matchedUri]);

        return $hasAccessResult->HasAccess === 1;
    }
}