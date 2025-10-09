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
            "SELECT u.idusuario, u.nombre AS username, u.email, m.nombre AS menuname, m.route_name
            FROM usuario AS u
                INNER JOIN usuariorol AS ur
                    ON u.idusuario = ur.idusuario
                INNER JOIN menurol AS mr
                    ON ur.idrol = mr.idrol
                INNER JOIN menu AS m
                    ON mr.idmenu = m.idmenu
            WHERE m.route_name IS NOT NULL";
    }
}
