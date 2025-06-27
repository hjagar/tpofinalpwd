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
                    <strong>Compra #{{ $compra->idcompra }}</strong><br>
                    {{  $compra->product }} <br>
                    Fecha: {{ $compra->fecha }}<br>
                    Total: ${{ number_format($compra->total, 2) }}
                  </div>
                  <a href="{{ route('my_purchases.show', [$compra->idcompra]) }}" class="btn btn-primary btn-sm">Ver
                    Detalles</a>
                </li>
              @endforeach
            </ul>
          @else
            <p class="text-center">No tienes compras registradas.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
