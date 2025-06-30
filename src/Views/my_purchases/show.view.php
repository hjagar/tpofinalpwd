@extends('layouts.main')
@section('title')
  Detalle de Compra
@endsection
@section('content')
  <h1 class="mb-4 text-center">Detalle de Compra #{{ $purchase->idcompra }}</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3"><span class="fw-bold">Cliente:</span> {{ $purchase->usuario }}</h5>
          <p class="mb-1"><span class="fw-bold">Fecha:</span> {{ $purchase->fecha }}</p>
          <p class="mb-1"><span class="fw-bold">Total:</span> <span class="text-success">${{ number_format($purchase->total, 2, ',', '.') }}</span></p>
          <p class="mb-3"><span class="fw-bold">Estado:</span> {{ $purchase->estado_emoji }} <span class="badge {{ $purchase->estado_badge }} text-dark">{{ $purchase->estado }}</span></p>
          <p class="fw-bold mb-2">Productos:</p>
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>Producto</th>
                  <th>Precio Unitario</th>
                  <th>Cantidad</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($purchaseDetails as $detail)
                <tr>
                  <td>{{ $detail->producto }}</td>
                  <td>${{ number_format($detail->precio, 2, ',', '.') }}</td>
                  <td>{{ $detail->cantidad }}</td>
                  <td>${{ number_format($detail->precio * $detail->cantidad, 2, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-start gap-2 mt-3">
            <a href="{{ route('my_purchases.index') }}" class="btn btn-secondary">Volver a Mis Compras</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection