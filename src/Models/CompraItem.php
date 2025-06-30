<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class CompraItem extends Model
{
    protected array $relations = [
        'compra' => [ModelRelationType::BelongsTo, Compra::class],
        'producto' => [ModelRelationType::BelongsTo, Producto::class],
    ];
}
