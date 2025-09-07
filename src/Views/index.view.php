@extends('layouts.main')

@section('title')
  Tienda Tuya
@endsection

@section('content')
  <section class="productos">
    <h2>Productos</h2>
    <p>Estos son los productos que tenemos disponibles en nuestra tienda.</p>
    <div class="grid-productos">
      @foreach ($productos as $producto)
        <article class="producto">
          <img src="https://via.assets.so/game.png?id={{ $producto->idproducto }}&q=95&w=200&h=150&fit=fill"
            alt="{{ $producto->nombre }}" />
          <h3>{{ $producto->nombre }}</h3>
          <p>$ {{ number_format($producto->precio, 2, ',', '.') }}</p>
          @if(auth()->check())
          <button id="producto_{{ $producto->idproducto }}" r-click="scope.addToCart({{ $producto->idproducto }})">Agregar</button>
          @endif
        </article>
      @endforeach
    </div>
  </section>
@endsection

@section('javascript')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      reactive.init({
        state: {
          itemCount: parseInt('{!! $cart->count() !!}')
        },
        scope: {
          async addToCart(id) {
            const response = await http.get(route('cart.add', {id}));
            
            if (response.status === http.status.ok) {
              const responseData = JSON.parse(response.content);
              if(responseData.success){
                reactive.setState('itemCount', reactive.state.itemCount + 1);
              }
              else{
                alert(responseData.message);
              }               
            }
          }
        }
      });
    });
  </script>
@endsection
