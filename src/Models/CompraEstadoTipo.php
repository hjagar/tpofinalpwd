<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class CompraEstadoTipo
 * Representa un tipo de estado de una compra en el sistema.
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 */
class CompraEstadoTipo extends Model
{
    protected array $relations = [
        'estado' => [ModelRelationType::HasMany, CompraEstado::class],
    ];
}
