<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelations;

class Menu extends Model
{
    protected array $fillable = [
        'nombre',
        'descripcion',
        'idpadre',
        'route_name',
        'html_id',
        'has_user'
    ];

    protected array $relations = [
        'roles' => [ModelRelations::BelongsToMany, Rol::class, 'menurol'],
        'submenus' => [ModelRelations::HasMany, Menu::class, 'idmenu', 'idpadre'],
        'padre' => [ModelRelations::BelongsTo, Menu::class, 'idmenu', 'idpadre']
    ];

    public function sqlMenusByName(): string
    {
        $sql =
            "WITH RECURSIVE menu_recursivo AS (
                SELECT *
                FROM menu
                WHERE nombre = ?
                UNION ALL
                SELECT m.*
                FROM menu m
                INNER JOIN menu_recursivo mr ON m.idpadre = mr.idmenu
            )
            SELECT mr1.idmenu, mr1.nombre, mr1.idpadre, mr1.route_name, mr1.html_id, mr1.has_user, GROUP_CONCAT(mr2.idrol) AS role_ids,
                GROUP_CONCAT(r.nombre) AS role_names
            FROM menu_recursivo AS mr1
                INNER JOIN menurol AS mr2
                    ON mr1.idmenu = mr2.idmenu
                INNER JOIN rol AS r
                    ON mr2.idrol = r.idrol
            WHERE mr1.nombre <> ?
            GROUP BY mr1.idmenu, mr1.nombre, mr1.idpadre, mr1.route_name, mr1.html_id, mr1.has_user;";

        return $sql;
    }
}
