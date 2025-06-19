@extends('layouts.auth')

@section('title')
  Inicio de sesión
@endsection

@section('content')
  <section class="auth-form">
    <h2>Inicio de sesión</h2>
    <form method="POST" action="{{ route('login.index') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>
  </section>
@endsection
