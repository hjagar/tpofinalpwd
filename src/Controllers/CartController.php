<?php

namespace App\Controllers;

class CartController
{
    /**
     * Display the cart page.
     *
     * @return void
     */
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', ['cart' => $cart]);
    }


    /**
     * Add a product to the cart.
     *
     * @param int $productId
     * @return void
     */
    public function add($productId)
    {
        $this->addToCart($productId);
        return redirect('cart.index');
    }

    /**
     * Remove a product from the cart.
     *
     * @param int $productId
     * @return void
     */
    public function remove($productId)
    {
        $this->removeFromCart($productId);
        return redirect('cart.index');
    }

    private function getCart()
    {
        // Aquí se implementaría la lógica para obtener el carrito del usuario
        // Por ejemplo, desde la sesión o una base de datos
        return $_SESSION['cart'] ?? [];
    }

    private function addToCart($productId)
    {
        // Aquí se implementaría la lógica para agregar un producto al carrito
        // Por ejemplo, agregarlo a la sesión o a una base de datos
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
    }

    private function removeFromCart($productId)
    {
        // Aquí se implementaría la lógica para eliminar un producto del carrito
        // Por ejemplo, eliminarlo de la sesión o de una base de datos
        unset($_SESSION['cart'][$productId]);
    }
}
