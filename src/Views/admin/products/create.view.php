@extends('layouts.admin')
@section('title')
  Crear producto
@endsection
@section('content')
  <h1>Crear Producto</h1>
  <form action="{{ route('admin.products.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="descripcion">Descripción</label>
      <input type="text" class="form-control" id="descripcion" name="descripcion">
    </div>
    <div class="form-group">
      <label for="precio">Precio</label>
      <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
    </div>
    <div class="form-group">
      <label for="stock">Cantidad en Stock</label>
      <input type="number" class="form-control" id="stock" name="stock" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Producto</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Volver a Productos</a>
  </form>
@endsection
