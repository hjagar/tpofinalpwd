<?php
namespace App\Controllers;

use App\Models\Menu;
use PhpMvc\Framework\View\ViewComposer;

class AppController {
    public function boot() {
        $menu = Menu::all();
        ViewComposer::share('menu', $menu);
    }
}