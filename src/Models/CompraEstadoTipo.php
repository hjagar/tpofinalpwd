<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class CompraEstadoTipo extends Model
{
    protected array $relations = [
        'estado' => [ModelRelations::HasMany, CompraEstado::class],
    ];
}
