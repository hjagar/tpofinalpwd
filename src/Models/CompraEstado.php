<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class CompraEstado extends Model
{
    protected array $relations = [
        'tipo' => [ModelRelations::BelongsTo, CompraEstadoTipo::class],
        'compra' => [ModelRelations::BelongsTo, Compra::class],
    ];
}
