@extends('layouts.main')

@section('title')
Tienda Tuya
@endsection

@section('content')
<section class="productos">
    <h2>Productos</h2>
    <div class="grid-productos">
        @foreach($productos as $producto)
        <article class="producto">
            <img src="https://via.assets.so/game.png?id={{ $producto->idproducto }}&q=95&w=200&h=150&fit=fill" alt="{{ $producto->nombre }}" />
            <h3>{{ $producto->nombre }}</h3>
            <p>$ {{ number_format($producto->precio, 2, ',', '.')  }}</p>
            <button>Agregar</button>
        </article>
        @endforeach
    </div>
</section>
@endsection