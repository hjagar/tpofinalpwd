<?php

namespace App\Security;

use App\Models\Usuario;
use PhpMvc\Framework\Security\AuthorizationInterface;
use PhpMvc\Framework\Http\Request;

class AuthorizationManager implements AuthorizationInterface
{
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