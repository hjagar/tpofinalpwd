@extends('layouts.admin')
@section('title')
  Crear Usuario
@endsection
@section('content')
  <h1>Crear Usuario</h1>
  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Usuario</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver a Usuarios</a>
  </form>
@endsection
