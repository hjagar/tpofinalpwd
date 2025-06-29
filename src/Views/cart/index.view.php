@extends('layouts.main')
@section('title')
  Carrito de Compras
@endsection
@section('content')
  <h1 class="mb-4 text-center">Carrito de Compras</h1>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body">
          @if (count($cart) > 0)
            <ul class="list-group">
              @foreach ($cart as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong>{{ $item->product_name }}</strong><br>
                    Cantidad: {{ $item->quantity }}<br>
                    Precio: ${{ number_format($item->price, 2) }}
                  </div>
                  <form action="{{ route('cart.remove', [$item->id]) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                  </form>
                </li>
              @endforeach
            </ul>
            <div class="mt-3">
              <strong>Total: ${{ number_format($cart->sum('price'), 2) }}</strong>
            </div>
            <div class="d-flex justify-content-end mt-3">
              <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Finalizar Compra</button>
              </form>
            </div>
          @else
            <p class="text-center">Tu carrito está vacío.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
