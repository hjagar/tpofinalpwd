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
          @if ($cart->count() > 0)
            <ul class="list-group">
              @foreach ($productos as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong>{{ $item->nombre }}</strong><br>
                    Cantidad: {{ $cart->itemCount($item->idproducto) }}<br>
                    Precio: ${{ number_format($item->precio, 2, ',', '.') }}<br>
                    Total: ${{ number_format($item->precio * $cart->itemCount($item->idproducto), 2, ',', '.') }}
                  </div>
                  <form action="{{ route('cart.remove', [$item->idproducto]) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">⊘</button>
                  </form>
                </li>
              @endforeach
            </ul>
            <div class="d-flex justify-content-end mt-3">
              <strong>Total: ${{ number_format($item->precio, 2, ',', '.') }}</strong>
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