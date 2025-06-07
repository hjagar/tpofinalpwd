<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Compra extends Model
{
    protected array $relations = [
        'user' => [ModelRelations::BelongsTo, Usuario::class],
        'items' => [ModelRelations::HasMany, CompraItem::class],
        'estados' => [ModelRelations::HasMany, CompraEstado::class]
    ];
}
