@extends('layouts.admin')
@section('title')
  Actualizar Producto
@endsection
@section('content')
  <h1 class="mb-4 text-center">Actualizar Producto</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.products.update', [$producto->idproducto]) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $producto->descripcion }}">
            </div>
            <div class="mb-3">
              <label for="precio" class="form-label">Precio</label>
              <input type="number" class="form-control" id="precio" name="precio" value="{{ $producto->precio }}"
                step="0.01" required>
            </div>
            <div class="mb-3">
              <label for="stock" class="form-label">Cantidad en Stock</label>
              <input type="number" class="form-control" id="stock" name="stock" value="{{ $producto->stock }}"
                required>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Actualizar Producto</button>
              <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Volver a Productos</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
