<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class Usuario
 * Representa un usuario en el sistema.
 *
 * @property int $id
 * @property string $usnombre
 * @property string $usmail
 * @property string $uspass
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
        $role = Rol::where(['rodescripcion' => $roleName])->first();
        if ($role) {
            self::rawQuery('sqlAssignRole', [$this->idusuario, $role->idrol]);
        }
    }

    public function sqlAssignRole(){
        $sql =
            "INSERT INTO usuariorol (idusuario, idrol) VALUES(?, ?);";
        return $sql;
    }
}
