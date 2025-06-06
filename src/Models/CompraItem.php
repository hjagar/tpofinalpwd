<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class CompraItem extends Model
{
    protected array $relations = [
        'compra' => [ModelRelations::BelongsTo, Compra::class],
        'producto' => [ModelRelations::BelongsTo, Producto::class],
    ];
}
