<?php

namespace App\Bussiness;

use JsonSerializable;

class Cart implements JsonSerializable
{
    private array $products = [];

    public static function instance()
    {
        return $_SESSION['cart'] ?? new Cart();
    }

    public function find($id)
    {
        return $this->products[$id] ?? 0;
    }

    public function update($id, $count): int
    {
        $previousCount = $this->find($id);
        $this->products[$id] = $count;
        return $previousCount - $count;
    }

    public function add($id)
    {
        $this->products[$id] = ($this->products[$id] ?? 0) + 1;
        $this->save();
    }

    public function remove($id): int
    {
        $returnValue = $this->products[$id];
        unset($this->products[$id]);
        $this->save();
        return $returnValue;
    }

    public function removeOne($id): int
    {
        $previousStock = $this->products[$id];
        $this->products[$id] = ($this->products[$id] ?? 0) - 1;
        $currentStock = $this->products[$id];
        $returnValue = $previousStock - $currentStock;

        if (count($this->products[$id]) === 0) {
            $this->remove($id);
        }

        $this->save();
        return $returnValue;
    }

    public function count(): int
    {
        return array_reduce($this->products, fn($carry, $item) => $carry += $item, 0);
    }

    public function getProductIds()
    {
        return array_keys($this->products);
    }

    public function itemCount($id)
    {
        return $this->products[$id];
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    private function save()
    {
        $_SESSION['cart'] = $this;
    }
}
