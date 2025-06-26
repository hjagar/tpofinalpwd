@extends('layouts.admin')
@section('title')
Roles
@endsection
@section('content')
<h1 class="mb-4 text-center">Roles</h1>
<div class="mb-3 text-end">
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
        Crear Rol
    </a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
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
                    <a href="{{ route('admin.roles.edit', [$role->idrol]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                    <form action="{{ route('admin.roles.destroy', [$role->idrol]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este rol?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection