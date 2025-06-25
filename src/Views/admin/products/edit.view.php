@extends('layouts.admin')
@section('title')
  Actualizar Producto
@endsection
@section('content')
  <h1>Actualizar Producto</h1>
  <form action="{{ route('admin.products.update', [$producto->idproducto]) }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
    </div>
    <div class="form-group">
      <label for="descripcion">Descripción</label>
      <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $producto->descripcion }}">
    </div>
    <div class="form-group">
      <label for="precio">Precio</label>
      <input type="number" class="form-control" id="precio" name="precio" value="{{ $producto->precio }}"
        step="0.01" required>
    </div>
    <div class="form-group">
      <label for="stock">Cantidad en Stock</label>
      <input type="number" class="form-control" id="stock" name="stock" value="{{ $producto->stock }}"
        required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Volver a Productos</a>
  </form>
@endsection
