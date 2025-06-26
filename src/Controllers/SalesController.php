<?php

namespace App\Controllers;

class SalesController
{
    public function index()
    {
        // Aquí iría la lógica para listar las ventas
        $sales = [
            [
                'id' => 1,
                'customer' => 'Juan Pérez',
                'date' => '2024-06-01',
                'total' => 1500.00,
                'product' => 'Notebook Lenovo',
                'status' => 'Completada'
            ],
            [
                'id' => 2,
                'customer' => 'María Gómez',
                'date' => '2024-06-02',
                'total' => 2300.50,
                'product' => 'Smartphone Samsung',
                'status' => 'Pendiente'
            ],
            [
                'id' => 3,
                'customer' => 'Carlos López',
                'date' => '2024-06-03',
                'total' => 980.75,
                'product' => 'Auriculares Sony',
                'status' => 'Cancelada'
            ]
        ];

        return view('admin.sales.index', compact('sales'));
    }

    public function edit(int $id)
    {
        // Aquí iría la lógica para editar una venta específica
        // Por simplicidad, retornamos una vista de edición con datos ficticios
        $sale = [
            'id' => $id,
            'customer' => 'Juan Pérez',
            'date' => '2024-06-01',
            'total' => 1500.00,
            'product' => 'Notebook Lenovo',
            'status' => 'Completada'
        ];

        return view('admin.sales.edit', compact('sale'));
    }
}
