<?php

namespace App\Controllers;

use App\Bussiness\Cart;
use App\Bussiness\Product;
use App\Models\Producto;
use PhpMvc\Framework\Http\Request;

class CartController
{
    public function index()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $productos = Producto::where(['idproducto' => $productIds])->get();

        return view('cart.index', compact('productos'));
    }

    public function indexAjax()
    {
        return view('cart.index-ajax');
    }

    public function indexSsr()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $productos = Producto::where(['idproducto' => $productIds])->get();
        $products = [];
        $itemCount = 0;
        $total = 0;

        foreach ($productos as $producto) {
            $quantity = $cart->itemCount($producto->idproducto);
            $product = new Product($producto->idproducto, $producto->nombre, $producto->precio, $quantity);
            $itemCount += $quantity;
            $total += $product->total();
            $products[] = $product;
        }

        $data = json_encode(['products' => $products, 'itemCount' => $itemCount, 'total' => $total]);

        return view('cart.index-ssr', compact('data'));
    }

    public function cartProducts()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $productos = Producto::where(['idproducto' => $productIds])->get();
        $products = [];
        $itemCount = 0;
        $total = 0;

        foreach ($productos as $producto) {
            $quantity = $cart->itemCount($producto->idproducto);
            $product = new Product($producto->idproducto, $producto->nombre, $producto->precio, $quantity);
            $itemCount += $quantity;
            $total += $product->total();
            $products[] = $product;
        }

        return json(['products' => $products, 'itemCount' => $itemCount, 'total' => $total]);
    }

    public function add($id)
    {
        $this->addToCart($id);
        $producto = Producto::find($id);

        return json("Producto {$producto->nombre} agregado con exito");
    }

    public function remove($id)
    {
        $this->removeFromCart($id);
        $producto = Producto::find($id);
        return json("Producto {$producto->nombre} removido con exito");
    }

    public function removeOne($id)
    {
        $this->removeOneFromCart($id);
        $producto = Producto::find($id);
        return json("Producto {$producto->nombre} removido con exito");
    }

    public function buy(Request $request)
    {
        // TODO: hacer la compra acá
    }

    private function getCart(): Cart
    {
        return Cart::instance();
    }

    private function addToCart($productId)
    {
        $this->getCart()->add($productId);
    }

    private function removeFromCart($productId)
    {
        $this->getCart()->remove($productId);
    }

    private function removeOneFromCart($productId)
    {
        $this->getCart()->removeOne($productId);
    }
}
