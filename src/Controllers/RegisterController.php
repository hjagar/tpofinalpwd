<?php

namespace App\Controllers;

use App\Enums\Roles;
use App\Models\Usuario;
use PhpMvc\Framework\Http\Request;

/**
 * Class RegisterController
 * Maneja el registro de usuarios.
 */
class RegisterController
{
    /**
     * Muestra el formulario de registro.
     *
     * @return \PhpMvc\Framework\Http\Response
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     *
     * @param Request $request
     * @return \PhpMvc\Framework\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = new Usuario();
        $usuario->fill($request->all());
        $usuario->password = password_hash($request->password, PASSWORD_DEFAULT);
        $usuario->save();
        $usuario->assignRole(Roles::CLIENTE);

        redirect('auth.index');
    }
}
