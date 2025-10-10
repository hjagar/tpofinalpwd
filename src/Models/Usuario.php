<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class Usuario
 * Representa un usuario en el sistema.
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 */
class Usuario extends Model
{
    protected array $fillable = ['nombre', 'email'];

    protected array $relations = [
        'roles' => [ModelRelationType::BelongsToMany, Rol::class, 'usuariorol'],
        'compras' => [ModelRelationType::HasMany, Compra::class],
    ];

    /**
     * Asigna un rol al usuario.
     *
     * @param string $roleName
     * @return void
     */
    public function assignRole(string $roleName): void
    {
        $role = Rol::where(['nombre' => $roleName])->first();
        
        if ($role) {
            self::rawQuery('sqlAssignRole', [$this->idusuario, $role->idrol]);
        }
    }
 
    public function sqlAssignRole(){
        $sql =
            "INSERT INTO usuariorol (idusuario, idrol) VALUES(?, ?);";
        return $sql;
    }

    public function sqlAuthorizationCheck() {
        $sql = 
            "SELECT 1 AS HasAccess
            FROM (
            SELECT ur.idusuario, CONCAT('/', REPLACE(REPLACE(m.route_name, '.index', ''), '.', '/')) AS route_name
            FROM usuariorol AS ur
                INNER JOIN menurol AS mr
                    ON ur.idrol = mr.idrol
                INNER JOIN menu AS m
                    ON mr.idmenu = m.idmenu
            WHERE m.route_name IS NOT NULL) AS ivUserRoutes
            WHERE idusuario = ?
            AND route_name = ?
            UNION ALL
            SELECT 0 AS HasAccess
            ORDER BY 1 DESC
            LIMIT 1";

        return $sql;
    }
}
