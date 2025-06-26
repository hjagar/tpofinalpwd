@extends('layouts.admin')
@section('title')
Usuarios
@endsection
@section('content')
<h1 class="mb-4 text-center">Usuarios</h1>
<div class="mb-3 text-end">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        Crear Usuario
    </a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->idusuario }}</td>
                <td>{{ $user->nombre }}</td>
                <td>{{ $user->email }}</td>
                <td>Cliente</td>
                <td>
                    <a href="{{ route('admin.users.edit', [$user->idusuario]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                    <form action="{{ route('admin.users.destroy', [$user->idusuario]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este usuario?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection