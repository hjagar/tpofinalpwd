<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class Rol extends Model
{
    protected array $relations = [
        'usuarios' => [ModelRelationType::BelongsToMany, Usuario::class, 'usuariorol'],
        'menus' => [ModelRelationType::BelongsToMany, Menu::class, 'menurol'],
    ];
}
