<?php

namespace App\Controllers;

use App\Models\Rol;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Http\HttpStatus;

class RoleController
{
    public function index()
    {
        $roles = Rol::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $role = new Rol();
        $role->fill($request->all());
        $role->save();

        redirect('admin.roles.index');
    }
    public function edit(int $id)
    {
        $role = Rol::find($id);
        if (!$role) {
            abort(HttpStatus::NOT_FOUND->value, 'Rol no encontrado');
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, int $id)
    {
        $role = Rol::find($id);
        if (!$role) {
            abort(HttpStatus::NOT_FOUND->value, 'Rol no encontrado');
        }
        $role->fill($request->all());
        $role->save();

        redirect('admin.roles.index');
    }
    /**
     * Delete a role by ID.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id)
    {
        $role = Rol::find($id);
        if (!$role) {
            abort(HttpStatus::NOT_FOUND->value, 'Rol no encontrado');
        }
        $role->delete();

        redirect('admin.roles.index');
    }
}
