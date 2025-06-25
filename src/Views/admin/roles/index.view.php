@extends('layouts.admin')
@section('title')
Roles
@endsection
@section('content')
<h1>Roles</h1>
<a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">
    Crear Rol
</a>
<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @foreach($roles as $role)
        <tr>
            <td>{{ $role->idrol }}</td>
            <td>{{ $role->nombre }}</td>
            <td>
                <a href="{{ route('admin.roles.edit', [$role->idrol]) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('admin.roles.destroy', [$role->idrol]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection