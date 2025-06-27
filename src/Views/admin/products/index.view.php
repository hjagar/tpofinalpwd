@extends('layouts.admin')
@section('title')
Productos
@endsection
@section('content')
<div class="container py-4">
  <h1 class="mb-4 text-center">Productos</h1>
  <div class="mb-3 text-end">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
      Crear Producto
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>Id</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($productos as $producto)
        <tr>
          <td>{{ $producto->idproducto }}</td>
          <td>{{ $producto->nombre }}</td>
          <td>{{ $producto->descripcion }}</td>
          <td>{{ $producto->precio }}</td>
          <td>{{ $producto->stock }}</td>
          <td>
            <a href="{{ route('admin.products.edit', [$producto->idproducto]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
            <form action="{{ route('admin.products.destroy', [$producto->idproducto]) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea eliminar este producto?')">Eliminar</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection