<?php

namespace App\Controllers;

use App\Models\Compra;

class MyPurchasesController
{
    /**
     * Display the user's purchases.
     *
     * @return void
     */
    public function index()
    {
        $compras = Compra::rawQueryAll('sqlMyPurchases', [auth()->user()->idusuario]);
        return view('my_purchases.index', compact('compras'));
    }

    /**
     * Display the details of a specific purchase.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $purchase = Compra::rawQueryOne('sqlCompraOne', [$id]);
        $purchaseDetails = Compra::rawQueryAll('sqlMyPurchasesDetails', [$id]);

        if (!$purchase) {
            redirect('my_purchases.index')->with('error', 'Compra no encontrada.');
        }

        return view('my_purchases.show', compact('purchase', 'purchaseDetails'));
    }
}
