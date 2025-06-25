@extends('layouts.admin')
@section('title')
  Actualizar Menú
@endsection
@section('content')
  <h1>Actualizar Menú</h1>
  <form action="{{ route('admin.menus.update', [$menu->idmenu]) }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $menu->nombre }}" required>
    </div>
    <div class="form-group">
      <label for="descripcion">Descripción</label>
      <input type="text" class="form-control" id="descripcion" name="descripcion"
        value="{{ $menu->descripcion }}">
    </div>
    <div class="form-group">
      <label for="idpadre">Menú Padre</label>
      <select class="form-control" id="idpadre" name="idpadre">
        <option value="">Ninguno</option>
        @foreach ($menus as $m)
          <option value="{{ $m->idmenu }}" {{ $menu->idpadre == $m->idmenu ? 'selected' : '' }}>
            {{ $m->nombre }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="route_name">Nombre de Ruta</label>
      <input type="text" class="form-control" id="route_name" name="route_name" value="{{ $menu->route_name ?? '' }}">
    </div>
    <div class="form-group">
      <label for="html_id">HTML ID</label>
      <input type="text" class="form-control" id="html_id" name="html_id" value="{{ $menu->html_id ?? '' }}">
    </div>
    <div class="form-group">
      <label for="has_user">Necesita Usuario</label>
      <select class="form-control" id="has_user" name="has_user">
        <option value="0" {{ $menu->has_user ? '' : 'selected' }}>No</option>
        <option value="1" {{ $menu->has_user ? 'selected' : '' }}>Sí</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Menú</button>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
@endsection
