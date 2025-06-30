<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class CompraEstado extends Model
{
    protected array $relations = [
        'tipo' => [ModelRelationType::BelongsTo, CompraEstadoTipo::class],
        'compra' => [ModelRelationType::BelongsTo, Compra::class],
    ];
}
