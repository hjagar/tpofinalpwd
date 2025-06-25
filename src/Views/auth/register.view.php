@extends('layouts.auth')

@section('title')
Registro de usuario
@endsection

@section('javascript')
<script>
function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirm').value;

    if (password !== confirmPassword) {
        alert('Las contraseñas no coinciden.');
        return false;
    }
    return true;
}
</script>

@endsection

@section('content')
<section class="auth-form">
    <h2>Registrarse</h2>
    <form action="{{ route('register.index') }}" method="POST" onsubmit="return validateForm()">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" autocomplete="off" required>
        </div>

        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</section>
@endsection