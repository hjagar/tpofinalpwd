<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Producto extends Model
{
    protected array $relations = [
        'items' => [ModelRelations::HasMany, CompraItem::class],
    ];
}
