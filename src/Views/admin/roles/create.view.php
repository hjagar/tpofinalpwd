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
          <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Crear Rol</button>
              <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Volver a Roles</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
