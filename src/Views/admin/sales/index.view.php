@extends('layouts.admin')
@section('title')
  Ventas
@endsection
@section('content')
  <div class="container py-4">
    <h1 class="mb-4 text-center">Ventas</h1>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Productos</th>
            <th>Precio</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $venta)
            <tr>
              <td>{{ $venta->idcompra }}</td>
              <td>{{ $venta->fecha }}</td>
              <td>{{ $venta->productos }}</td>
              <td class="text-end">
                <div class="pe-5">${{ number_format($venta->total, 2, ',', '.') }}</div>
              </td>
              <td>{{ $venta->usuario }}</td>
              <td>{{ $venta->estado_emoji }} 
                <span class="badge {{ $venta->estado_badge }}">
                  {{ $venta->estado }}
                </span>
              </td>
              <td class="text-center">
                <a href="{{ route('admin.sales.edit', [$venta->idcompra]) }}" class="btn btn-warning btn-sm me-1">Editar</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
