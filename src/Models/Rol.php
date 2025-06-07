<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Rol extends Model
{
    protected array $relations = [
        'usuarios' => [ModelRelations::BelongsToMany, Usuario::class, 'usuariorol'],
        'menus' => [ModelRelations::BelongsToMany, Menu::class, 'menurol'],
    ];
}
