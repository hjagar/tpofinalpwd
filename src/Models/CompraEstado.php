<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class CompraEstado
 * Representa un estado de una compra en el sistema.
 *
 * @property int $id
 * @property int $idcompra
 * @property int $idcompraestadotipo
 * @property string $fechainicio
 * @property string $fechafin
 */
class CompraEstado extends Model
{
    protected array $relations = [
        'tipo' => [ModelRelationType::BelongsTo, CompraEstadoTipo::class],
        'compra' => [ModelRelationType::BelongsTo, Compra::class],
    ];
}
