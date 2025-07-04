<?php

namespace App\Controllers;

use App\Bussiness\Cart;
use App\Models\Menu;
use PhpMvc\Framework\View\ViewComposer;

class AppController
{
    public function boot()
    {
        $headerMenu = Menu::rawQueryAll('sqlMenusByName', ['Header', 'Header']);
        $mainMenu = $this->getMainMenu();
        $cart = Cart::instance();
        ViewComposer::share('headerMenu', $headerMenu);
        ViewComposer::share('mainMenu', $mainMenu);
        ViewComposer::share('cart', $cart);
    }

    private function getMainMenu()
    {
        $mainMenu = Menu::rawQueryAll('sqlMenusByName', ['Main', 'Main']);
        $mainMenuItemIds = array_map(fn($item) => $item->idmenu, $mainMenu);
        $mainMenuFinal = [];

        foreach ($mainMenu as $item) {
            if (!in_array($item->idpadre, $mainMenuItemIds)) {
                $mainMenuFinal[] = $item;
            } else {
                $parentKey = array_find_key($mainMenuFinal, fn($parentItem) => $parentItem->idmenu === $item->idpadre);
                if ($parentKey !== false) {
                    if (!$mainMenuFinal[$parentKey]->hasProperty('children')) {
                        $mainMenuFinal[$parentKey]->children = [];
                    }

                    $mainMenuFinal[$parentKey]->children = array_merge([$item], $mainMenuFinal[$parentKey]->children);
                }
            }
        }

        return $mainMenuFinal;
    }
}
