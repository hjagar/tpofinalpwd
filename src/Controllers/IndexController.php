<?php

namespace App\Controllers;

use App\Models\Producto;

class IndexController {
    public function index() {
        $productos = Producto::all();

        return view('index', compact('productos'));
    }

    public function show($id) {
        $producto = Producto::find($id);

        return view('show', compact('producto'));
    }
}