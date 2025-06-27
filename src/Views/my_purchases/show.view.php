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
          <h5>Cliente: {{ $purchase->customer }}</h5>
          <p>Fecha: {{ $purchase->fecha }}</p>
          <p>Total: ${{ number_format($purchase->total, 2) }}</p>
          <p>Producto: {{ $purchase->product }}</p>
          <p>Estado: {{ $purchase->status }}</p>
          <div class="d-flex justify-content-start gap-2">
            <a href="{{ route('my_purchases.index') }}" class="btn btn-secondary">Volver a Mis Compras</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection