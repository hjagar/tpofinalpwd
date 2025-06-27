@extends('layouts.admin')
@section('title')
  Actualizar Usuario
@endsection
@section('content')
  <h1 class="mb-4 text-center">Actualizar Usuario</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.users.update', [$usuario->idusuario]) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="d-flex justify-content-start gap-2">
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
