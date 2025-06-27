@extends('layouts.admin')

@section('title')
Menús
@endsection

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-center">Menús</h1>
    <div class="mb-3 text-end">
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
            Crear Menú
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Padre</th>
                    <th>Nombre de Ruta</th>
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
                    <td>{{ $menu->route_name ?? '' }}</td>
                    <td>{{ $menu->html_id ?? '' }}</td>
                    <td>
                        <span class="badge {{ $menu->has_user ? 'bg-success' : 'bg-secondary' }}">
                            {{ $menu->has_user ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.menus.edit', [$menu->idmenu]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form action="{{ route('admin.menus.destroy', [$menu->idmenu]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este menú?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection