<?php

namespace App\Security;

use PhpMvc\Framework\Security\AuthorizationInterface;
use PhpMvc\Framework\Http\Request;

class AuthorizationManager implements AuthorizationInterface
{
    public function check(Request $request): bool
    {

        // TODO: verificar si el usuario que esta logueado tiene el rol para ver la pagina que le estan pidiendo
        return true;
    }
}