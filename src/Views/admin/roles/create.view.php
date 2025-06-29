@extends('layouts.admin')
@section('title')
Crear Rol
@endsection
@section('content')
<h1 class="mb-4 text-center">Crear Rol</h1>
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="needs-validation" novalidate>
          @csrf
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
            <div class="invalid-feedback" data-type="valueMissing">
              Por favor ingrese el nombre del rol.
            </div>
          </div>
          <div class="d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection