@extends('layouts.admin')
@section('title')
  Panel de Administración
@endsection
@section('content')
  <div class="container py-4">
    <h1 class="mb-4 text-center">Bienvenido al Panel de Administración</h1>
    <p class="text-center mb-4">Desde aquí puedes gestionar los menús, roles, usuarios y productos de tu aplicación.</p>
    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
      <a href="{{ route('admin.menus.index') }}" class="btn btn-primary btn-lg">Ir a Menús</a>
      <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-lg">Ir a Roles</a>
      <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-lg">Ir a Usuarios</a>
      <a href="{{ route('admin.products.index') }}" class="btn btn-info btn-lg text-white">Ir a Productos</a>
      <a href="{{ route('admin.sales.index') }}" class="btn btn-primary btn-lg">Ir a Ventas</a>
    </div>
    <div class="alert alert-info text-center" role="alert">
      Asegúrate de tener los permisos necesarios para acceder a estas secciones.<br>
      Si necesitas ayuda, consulta la documentación o contacta al administrador del sistema.
    </div>
    <p class="text-center">¡Disfruta gestionando tu aplicación!</p>
  </div>
@endsection
