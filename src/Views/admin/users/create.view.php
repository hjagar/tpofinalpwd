@extends('layouts.admin')
@section('title')
Crear Usuario
@endsection
@section('content')
<h1 class="mb-4 text-center">Crear Usuario</h1>
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
          @csrf
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
            <div class="invalid-feedback" data-type="valueMissing">
              Por favor ingrese el nombre del usuario.
            </div>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required
              pattern="[a-zA-Z0-9!#$%&'*\/=?^_`\{\|\}~\+\-]([\.]?[a-zA-Z0-9!#$%&'*\/=?^_`\{\|\}~\+\-])+@[a-zA-Z0-9]([^@&%$\/\(\)=?¿!\.,:;]|\d)+[a-zA-Z0-9][\.][a-zA-Z]{2,4}([\.][a-zA-Z]{2})?">
            <div class="invalid-feedback" data-type="valueMissing">
              Por favor ingrese un correo electrónico.
            </div>
            <div class="invalid-feedback" data-type="typeMismatch">
              Por favor ingrese un correo válido.
            </div>
            <div class="invalid-feedback" data-type="patternMismatch">
              Por favor ingrese un correo válido.
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback" data-type="valueMissing">
              Por favor ingrese la contraseña del usuario.
            </div>
          </div>
          <div class="d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection