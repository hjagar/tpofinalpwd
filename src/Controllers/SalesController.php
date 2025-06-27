<?php

namespace App\Controllers;

class SalesController
{
    public function index()
    {
        // Aquí iría la lógica para listar las ventas
        $sales = [
            (object)[
                'id' => 1,
                'customer' => 'Juan Pérez',
                'date' => '2024-06-01',
                'total' => 1500.00,
                'product' => 'Notebook Lenovo',
                'status' => 'Completada'
            ],
            (object)[
                'id' => 2,
                'customer' => 'María Gómez',
                'date' => '2024-06-02',
                'total' => 2300.50,
                'product' => 'Smartphone Samsung',
                'status' => 'Pendiente'
            ],
            (object)[
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
        $sale = (object)[
            'id' => $id,
            'customer' => 'Juan Pérez',
            'date' => '2024-06-01',
            'total' => 1500.00,
            'product' => 'Notebook Lenovo',
            'status' => 'Completada',
            'product_id' => 2,
            'customer_id' => 3,
            'quantity' => 5,
        ];

        $products = [
            (object)['id' => 1, 'name' => 'Producto A', 'price' => 1000],
            (object)['id' => 2, 'name' => 'Producto B', 'price' => 1500],
        ];
        $customers = [
            (object)['id' => 3, 'name' => 'Juan Pérez', 'email' => 'juan@mail.com'],
            (object)['id' => 4, 'name' => 'Ana Gómez', 'email' => 'ana@mail.com'],
        ];

        return view('admin.sales.edit', compact('sale', 'products', 'customers'));
    }

    public function update(int $id)
    {
        // Aquí iría la lógica para actualizar una venta específica
        // Por simplicidad, retornamos una redirección a la lista de ventas
        // En un caso real, se procesarían los datos del formulario y se actualizaría la base de datos

        // Simulación de actualización exitosa
        $_SESSION['message'] = 'Venta actualizada correctamente';

        return redirect('admin.sales.index');
    }
}
