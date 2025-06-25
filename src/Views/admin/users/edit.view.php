@extends('layouts.admin')
@section('title')
  Actualizar Usuario
@endsection
@section('content')
  <h1>Actualizar Usuario</h1>
  <form action="{{ route('admin.users.update', [$usuario->idusuario]) }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver a Usuarios</a>
  </form>
@endsection
