@extends('layouts.admin')
@section('title')
  Panel de Administración
@endsection
@section('content')
  <h1>Bienvenido al Panel de Administración</h1>
  <p>Desde aquí puedes gestionar los menús de tu aplicación.</p>
  <a href="{{ route('admin.menus.index') }}" class="btn btn-primary">Ir a Menús</a>
  <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Ir a Roles</a>
  <a href="{{ route('admin.users.index') }}" class="btn btn-success">Ir a Usuarios</a>
  <a href="{{ route('admin.products.index') }}" class="btn btn-info">Ir a Productos</a>
  <p class="mt-3">Asegúrate de tener los permisos necesarios para acceder a estas secciones.</p>
  <p>Si necesitas ayuda, consulta la documentación o contacta al administrador del sistema.</p>
  <p>¡Disfruta gestionando tu aplicación!</p>
@endsection
