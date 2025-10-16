<?php

namespace App\Controllers;

use App\Models\Usuario;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Http\HttpStatus;

class UserController
{
    public function index()
    {
        $users = Usuario::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $user = new Usuario();
        $user->fill($request->all());
        $user->save();

        redirect('admin.users.index');
    }

    public function edit(int $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            abort(HttpStatus::NOT_FOUND->value, 'Usuario no encontrado');
        }

        return view('admin.users.edit', compact('usuario'));
    }

    public function update(Request $request, int $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            abort(HttpStatus::NOT_FOUND->value, 'Usuario no encontrado');
        }
        $usuario->fill($request->all());
        $usuario->password = password_hash($request->password, PASSWORD_DEFAULT);
        $usuario->save();

        redirect('admin.users.index');
    }

    public function destroy(int $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            abort(HttpStatus::NOT_FOUND->value, 'Usuario no encontrado');
        }
        $usuario->delete();

        redirect('admin.users.index');
    }
}
