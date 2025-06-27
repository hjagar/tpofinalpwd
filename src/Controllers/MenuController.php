<?php

namespace App\Controllers;

use App\Models\Menu;
use PhpMvc\Framework\Http\Request;

class MenuController
{
    public function index()
    {
        //$menus = Menu::with(['padre', 'roles'])->get(); TODO
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('admin.menus.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $menu = new Menu();
        $menu->fill($request->all());
        $menu->save();

        redirect('admin.menus.index');
    }

    public function edit(int $id)
    {
        $menu = Menu::find($id);
        $menus = Menu::all();
        if (!$menu) {
            abort(404, 'Menu not found');
        }

        return view('admin.menus.edit', compact('menu', 'menus'));
    }

    public function update(Request $request, int $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            abort(404, 'Menu not found');
        }
        $menu->fill($request->all());
        $menu->save();

        redirect('admin.menus.index');
    }

    public function destroy(int $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            abort(404, 'Menu not found');
        }
        $menu->delete();

        redirect('admin.menus.index');
    }
}
