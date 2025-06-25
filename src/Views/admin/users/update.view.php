@extends('layouts.admin')
@section('title')
Actualizar Usuario
@endsection
@section('content')
<h1>Actualizar Usuario</h1>
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="usnombre">Nombre</label>
        <input type="text" class="form-control" id="usnombre" name="usnombre" value="{{ $user->usnombre }}" required>
    </div>
    <div class="form-group">
        <label for="usapellido">Apellido</label>
        <input type="text" class="form-control" id="usapellido" name="usapellido" value="{{ $user->usapellido }}" required>
    </div>
    <div class="form-group">
        <label for="usmail">Email</label>
        <input type="email" class="form-control" id="usmail" name="usmail" value="{{ $user->usmail }}" required>
    </div>
    <div class="form-group">
        <label for="uspassword">Contraseña</label>
        <input type="password" class="form-control" id="uspassword" name="uspassword">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Volver a Usuarios</a>
</form>
@endsection