@extends('layouts.admin')
@section('title')
  Actualizar Rol
@endsection
@section('content')
  <h1>Actualizar Rol</h1>
  <form action="{{ route('admin.roles.update', $role->idrol) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $role->nombre }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Rol</button>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-3">Volver a Roles</a>
  </form>
@endsection
