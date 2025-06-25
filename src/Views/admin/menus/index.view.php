@extends('layouts.admin')

@section('title')
Menús
@endsection

@section('content')
    <h1>Menús</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary mb-3">Crear Menú</a>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Padre</th>
                <th>URL</th>
                <th>Html Id</th>
                <th>Necesita Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->idmenu }}</td>
                <td>{{ $menu->nombre }}</td>
                <td>{{ $menu->descripcion }}</td>
                <td>{{ $menu->idpadre ?? 'Ninguno' }}</td>
                <td>{{ $menu->url ?? '' }}</td>
                <td>{{ $menu->html_id ?? '' }}</td>
                <td>{{ $menu->has_user ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.menus.edit', [$menu->idmenu]) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('admin.menus.destroy', [$menu->idmenu]) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection