<?php

namespace App\Controllers;

class MyPurchasesController
{
    /**
     * Display the user's purchases.
     *
     * @return void
     */
    public function index()
    {
        // Aquí se obtendrían las compras del usuario autenticado
        // Por ejemplo, usando un modelo de compra
        $compras = [
            (object)[
                'idcompra' => 1,
                'customer' => 'Juan Pérez',
                'fecha' => '2024-06-01',
                'total' => 1500.00,
                'product' => 'Notebook Lenovo',
                'status' => 'Completada'
            ]
        ];

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
        $purchase = (object)[
            'idcompra' => 1,
            'customer' => 'Juan Pérez',
            'fecha' => '2024-06-01',
            'total' => 1500.00,
            'product' => 'Notebook Lenovo',
            'status' => 'Completada'
        ]; // Obtener la compra por ID

        if (!$purchase) {
            return redirect('my_purchases.index')->with('error', 'Compra no encontrada.');
        }

        return view('my_purchases.show', compact('purchase'));
    }
}
