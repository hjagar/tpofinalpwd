<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Usuario extends Model
{
    protected array $relations = [
        'roles' => [ModelRelations::BelongsToMany, Rol::class, 'usuariorol'],
        'compras' => [ModelRelations::HasMany, Compra::class],
    ];
}
