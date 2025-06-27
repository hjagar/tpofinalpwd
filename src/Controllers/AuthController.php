<?php

namespace App\Controllers;

use PhpMvc\Framework\Http\Request;

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

        if (auth()->attempt($email, $password)) {
            $usuario = auth()->getDbUser('idusuario');
            $roles = $usuario->roles()->get();
            auth()->user()->roles = array_map(fn($role) => $role->nombre, $roles);
            redirect('home.index');
        } else {
            return view('auth.login', ['error' => 'Credenciales inválidas']);
        }
    }

    public function logout()
    {
        auth()->logout();
        redirect('home.index');
    }
}
