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
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $venta)
            <tr>
              <td>{{ $venta['id'] }}</td>
              <td>{{ $venta['product'] }}</td>
              <td>{{ $venta['quantity'] ?? 1 }}</td>
              <td>${{ number_format($venta['total'], 2) }}</td>
              <td>{{ $venta['customer'] }}</td>
              <td>
                <span class="badge
                  @if ($venta['status'] == 'Pendiente') bg-warning
                  @elseif($venta['status'] == 'Completada') bg-success
                  @else bg-secondary @endif">
                  {{ $venta['status'] }}
                </span>
              </td>
              <td>
                <a href="{{ route('admin.sales.edit', [$venta['id']]) }}" class="btn btn-primary btn-sm me-1">Editar</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
