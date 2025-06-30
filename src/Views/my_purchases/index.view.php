@extends('layouts.main')
@section('title')
  Mis Compras
@endsection
@section('content')
  <h1 class="mb-4 text-center">Mis Compras</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          @if (count($compras) > 0)
            <ul class="list-group">
              @foreach ($compras as $compra)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <span class="fw-bold">Compra #{{ $compra->idcompra }}</span><br>
                    <span class="text-muted">{{ $compra->productos }}</span><br>
                    <span class="small">Fecha: {{ $compra->fecha }}</span><br>
                    <span class="small">Total: <span class="text-success">${{ number_format($compra->total, 2, ',', '.') }}</span></span><br />
                    <span class="small">Estado: {{ $compra->estado_emoji }} <span class="badge {{ $compra->estado_badge }} text-dark">{{ $compra->estado }}</span>
                  </div>
                  <a href="{{ route('my_purchases.show', [$compra->idcompra]) }}" class="btn btn-outline-primary btn-sm">
                    Ver Detalles
                  </a>
                </li>
              @endforeach
            </ul>
          @else
            <p class="text-center text-muted">No tienes compras registradas.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection