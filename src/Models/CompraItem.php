<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class CompraItem
 * Representa un item de una compra en el sistema.
 *
 * @property int $id
 * @property int $idcompra
 * @property int $idproducto
 * @property int $cantidad
 */
class CompraItem extends Model
{
    protected array $relations = [
        'compra' => [ModelRelationType::BelongsTo, Compra::class],
        'producto' => [ModelRelationType::BelongsTo, Producto::class],
    ];
}
