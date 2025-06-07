<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Menu extends Model
{
    protected array $relations = [
        'roles' => [ModelRelations::BelongsToMany, Rol::class, 'menurol'],
        'submenus' => [ModelRelations::HasMany, Menu::class, 'idmenu', 'idpadre'],
    ];
}
