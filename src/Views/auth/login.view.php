@extends('layouts.auth')

@section('title')
  Inicio de sesión
@endsection

@section('javascript')
  <script>
    function validateForm() {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;

      if (!email || !password) {
        alert('Por favor, complete todos los campos.');
        return false;
      }
      return true;
    }
  </script>
@endsection

@section('content')
  <section class="auth-form">
    <h2>Inicio de sesión</h2>
    <form method="POST" action="{{ route('auth.login') }}" onsubmit="return validateForm()">
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
