<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

/**
 * Class Producto
 * Representa un producto en el sistema.
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property int $precio
 * @property int $stock
 */
class Producto extends Model
{
    protected array $relations = [
        'items' => [ModelRelationType::HasMany, CompraItem::class],
    ];
}
