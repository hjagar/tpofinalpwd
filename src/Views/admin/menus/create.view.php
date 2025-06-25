@extends('layouts.admin')
@section('title')
  Crear Menú
@endsection
@section('content')
  <h1>Crear Menú</h1>
  <form action="{{ route('admin.menus.store') }}" method="POST">
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
      <label for="idpadre">Menú Padre</label>
      <select class="form-control" id="idpadre" name="idpadre">
        <option value="">Ninguno</option>
        @foreach ($menus as $menu)
          <option value="{{ $menu->idmenu }}">{{ $menu->nombre }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="route_name">Nombre de Ruta</label>
      <input type="text" class="form-control" id="route_name" name="route_name">
    </div>
    <div class="form-group">
      <label for="html_id">HTML ID</label>
      <input type="text" class="form-control" id="html_id" name="html_id">
    </div>
    <div class="form-group">
      <label for="has_user">Necesita Usuario</label>
      <select class="form-control" id="has_user" name="has_user">
        <option value="0">No</option>
        <option value="1">Sí</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Crear Menú</button>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Volver a Menús</a>
  </form>
@endsection
