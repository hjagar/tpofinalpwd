@extends('layouts.admin')
@section('title')
  Productos
@endsection
@section('content')
  <h1>Productos</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
    Crear Producto
  </a>
  <table class="table">
    <thead>
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
            <a href="{{ route('admin.products.edit', [$producto->idproducto]) }}" class="btn btn-warning">Editar</a>
            <form action="{{ route('admin.products.destroy', [$producto->idproducto]) }}" method="POST"
              style="display:inline;">
              @csrf
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
