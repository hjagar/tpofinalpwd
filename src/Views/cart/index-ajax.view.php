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
          <ul r-show="itemCount" class="list-group">
            <li r-for(product in products) class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong>[[ product.name ]]</strong><br>
                Precio: $[[ product.price ]]<br>
                Cantidad: <input type="number" r-model="item.quantity"><br>
                Total: $[[ product.price * product.quantity ]]
              </div>
              <button r-click="scope.remove(product.id)" type="button" class="btn btn-danger btn-sm">⊘</button>
            </li>
          </ul>
          <div class="d-flex justify-content-end mt-3">
            <strong>Total: $ [[ total ]]</strong>
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
      const data = await JSON.parse(response.content);
      const [products, itemCount, total] = data;
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

          },
          async buy() {

          }
        }
      });
    });
  </script>
@endsection
