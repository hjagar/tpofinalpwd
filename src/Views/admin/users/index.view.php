@extends('layouts.admin')
@section('title')
Usuarios
@endsection
@section('content')
<h1>Usuarios</h1>
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">
    Crear Usuario
</a>
<table class="table">
    <thead>
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
                <a href="{{ route('admin.users.edit', [$user->idusuario]) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('admin.users.destroy', [$user->idusuario]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection