<?php

namespace App\Bussiness;

use JsonSerializable;

class Product implements JsonSerializable
{
    private float $total;
    public function __construct(private int $id, private string $name, private float $price, private int $quantity)
    {
        $this->total = $this->quantity * $this->price;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    public function total()
    {
        return $this->total;
    }
}
