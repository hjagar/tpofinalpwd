@extends('layouts.admin')
@section('title')
  Actualizar Menú
@endsection
@section('content')
  <h1 class="mb-4 text-center">Actualizar Menú</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.menus.update', [$menu->idmenu]) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $menu->nombre }}" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $menu->descripcion }}">
            </div>
            <div class="mb-3">
              <label for="idpadre" class="form-label">Menú Padre</label>
              <select class="form-select" id="idpadre" name="idpadre">
                <option value="">Ninguno</option>
                @foreach ($menus as $m)
                  <option value="{{ $m->idmenu }}" {{ $menu->idpadre == $m->idmenu ? 'selected' : '' }}>
                    {{ $m->nombre }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="route_name" class="form-label">Nombre de Ruta</label>
              <input type="text" class="form-control" id="route_name" name="route_name" value="{{ $menu->route_name ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="html_id" class="form-label">HTML ID</label>
              <input type="text" class="form-control" id="html_id" name="html_id" value="{{ $menu->html_id ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="has_user" class="form-label">Necesita Usuario</label>
              <select class="form-select" id="has_user" name="has_user">
                <option value="0" {{ $menu->has_user ? '' : 'selected' }}>No</option>
                <option value="1" {{ $menu->has_user ? 'selected' : '' }}>Sí</option>
              </select>
            </div>
            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Actualizar Menú</button>
              <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
