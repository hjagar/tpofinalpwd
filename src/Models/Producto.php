<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class Producto extends Model
{
    protected array $relations = [
        'items' => [ModelRelationType::HasMany, CompraItem::class],
    ];
}
