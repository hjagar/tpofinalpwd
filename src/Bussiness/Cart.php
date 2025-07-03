<?php

namespace App\Bussiness;

use JsonSerializable;

class Cart implements JsonSerializable
{
    private array $products = [];

    public static function instance() {
        return $_SESSION['cart'] ?? new Cart(); 
    }

    public function add($id)
    {
        $this->products[$id] = ($this->products[$id] ?? 0) + 1;
        $this->save();
    }

    public function remove($id)
    {
        unset($this->products[$id]);
        $this->save();
    }

    public function removeOne($id)
    {
        $this->products[$id] = ($this->products[$id] ?? 0) + 1;

        if (count($this->products[$id]) === 0) {
            $this->remove($id);
        }
        $this->save();
    }

    public function count(): int
    {
        return array_reduce($this->products, fn($carry, $item) => $carry += $item, 0);
    }

    public function getProductIds() {
        return array_keys($this->products);
    }

    public function itemCount($id) {
        return $this->products[$id];
    }

    public function jsonSerialize(): mixed
    {
        return [
            'products' => $this->products
        ];
    }

    private function save() {
        $_SESSION['cart'] = $this;
    }
}
