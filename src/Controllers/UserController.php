<?php

namespace App\Controllers;

use App\Models\Usuario;
use PhpMvc\Framework\Http\Request;

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
        $user = Usuario::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $user = Usuario::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }
        $user->fill($request->all());
        $user->save();

        redirect('admin.users.index');
    }

    public function delete(int $id)
    {
        $user = Usuario::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }
        $user->delete();

        redirect('admin.users.index');
    }
}
