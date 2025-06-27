<?php

namespace App\Controllers;

use App\Enums\Roles;
use PhpMvc\Framework\Http\Request;
use App\Models\Usuario;

class AuthController
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $usuario = Usuario::where(['email' => $email])->first();

        if ($usuario && password_verify($password, $usuario->password)) {
            $_SESSION['user'] = [
                'id' => $usuario->idusuario,
                'name' => $usuario->nombre,
                'roles' => [Roles::CLIENTE]
            ];
            redirect('home.index');
        } else {
            return view('auth.login', ['error' => 'Credenciales inválidas']);
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        redirect('home.index');
    }
}
