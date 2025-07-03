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
        <div r-html="message"></div>
        <ul r-show="itemCount" class="list-group">
          <li r-for="product in products" class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong><span r-text="product.name"></span></strong><br>
              Precio: $<span r-text="product.price"></span><br>
              Cantidad: <input id="quantity" name="quantity" type="number" r-model="product.quantity"
                r-onchange="scope.onQuantityChange(product)"><br>
              Total: $ <span r-text="product.total"></span>
            </div>
            <button r-click="scope.remove(product.id)" type="button" class="btn btn-danger btn-sm">⊘</button>
          </li>
        </ul>
        <div class="d-flex justify-content-end mt-3">
          <strong>Total: $ <span r-text="total"></span></strong>
        </div>
        <div class="d-flex justify-content-end mt-3">
          <button r-click="scope.buy()" type="button" class="btn btn-success">Finalizar Compra</button>
        </div>
        <p r-show="!itemCount" class="text-center">Tu carrito está vacío.</p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script>
  document.addEventListener('DOMContentLoaded', async function() {
    const response = await http.get(route('cart.products'));
    const responseData = JSON.parse(response.content);
    const {
      data: {
        products,
        total,
        itemCount
      }
    } = responseData;
    reactive.init({
      state: {
        itemCount,
        products,
        total
      },
      scope: {
        async addToCart(id) {
          const response = await http.get(route('cart.add', {
            id
          }));

          if (response.status === http.status.ok) {
            // TODO:  show toast con el mensaje
            reactive.setState('itemCount', reactive.state.itemCount + 1);
          }
        },
        async remove(id) {
          // TODO: hacer llamada al backend
          reactive.setState('itemCount', reactive.state.itemCount - 1);
        },
        async buy() {

        },
        onQuantityChange(product) {
          product.total = product.price * product.quantity;
          const products = reactive.state.products;                   
          const itemCount = products.reduce((sum, p) => sum + p.quantity, 0);
          const total = products.reduce((sum, p) => sum + p.price * p.quantity, 0);
          //reactive.setState('products', [...products]);
          reactive.updateItemBindings();
          reactive.setState('itemCount', itemCount);
          reactive.setState('total', total);
        },
      }
    });
  });
</script>
@endsection