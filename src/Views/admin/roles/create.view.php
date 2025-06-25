@extends('layouts.admin')
@section('title')
  Crear Rol
@endsection
@section('content')
  <h1>Crear Rol</h1>
  <form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Rol</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-3">Volver a Roles</a>
  </form>
@endsection
