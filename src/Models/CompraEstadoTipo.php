<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class CompraEstadoTipo extends Model
{
    protected array $relations = [
        'estado' => [ModelRelationType::HasMany, CompraEstado::class],
    ];
}
