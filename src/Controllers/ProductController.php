<?php

namespace App\Controllers;

use App\Models\Producto;
use PhpMvc\Framework\Http\Request;

class ProductController
{
    public function index()
    {
        $productos = Producto::all();

        return view('admin.products.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $producto = new Producto();
        $producto->fill($request->all());
        $producto->save();

        redirect('admin.products.index');
    }

    public function edit(int $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            abort(404, 'Producto not found');
        }
        return view('admin.products.edit', compact('producto'));
    }

    public function update(Request $request, int $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            abort(404, 'Producto not found');
        }
        $producto->fill($request->all());
        $producto->save();

        redirect('admin.products.index');
    }
    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            abort(404, 'Producto not found');
        }
        $producto->delete();

        redirect('admin.products.index');
    }
}
