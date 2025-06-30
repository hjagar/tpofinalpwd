<?php

namespace App\Models;

use PhpMvc\Framework\Data\Model;
use PhpMvc\Framework\Data\Constants\ModelRelationType;

class Compra extends Model
{
    protected array $relations = [
        'user' => [ModelRelationType::BelongsTo, Usuario::class],
        'items' => [ModelRelationType::HasMany, CompraItem::class],
        'estados' => [ModelRelationType::HasMany, CompraEstado::class]
    ];

    public function sqlComprasAll() {
        $sql =
            "SELECT
                c.idcompra,
                u.idusuario,
                ce.idcompraestado,
                cet.idcompraestadotipo,
                p.idproducto,
                c.fecha,
                u.nombre as usuario,
                u.email,
                p.nombre AS producto,
                cet.nombre AS estado,
                CASE
                    WHEN cet.nombre = 'iniciada' THEN '🛒'
                    WHEN cet.nombre = 'aceptada' THEN '✔️'
                    WHEN cet.nombre = 'enviada' THEN '📦'
                    WHEN cet.nombre = 'cancelada' THEN '❌'		
                END AS estado_emoji,
                CASE
                    WHEN cet.nombre = 'iniciada' THEN 'badge-primary'
                    WHEN cet.nombre = 'aceptada' THEN 'badge-warning'
                    WHEN cet.nombre = 'enviada' THEN 'badge-success'
                    WHEN cet.nombre = 'cancelada' THEN 'badge-danger'		
                END AS estado_badge,
                p.precio,
                ci.cantidad,
                (p.precio * ci.cantidad) AS total
            FROM compra AS c
                INNER JOIN (
                    SELECT ce.idcompra, MAX(ce.idcompraestado) AS idcompraestado
                    FROM compraestado AS ce
                    GROUP BY ce.idcompra
                ) AS ivce
                    ON c.idcompra = ivce.idcompra
                INNER JOIN compraestado AS ce
                    ON ivce.idcompraestado = ce.idcompraestado
                INNER JOIN compraestadotipo AS cet
                    ON ce.idcompraestadotipo = cet.idcompraestadotipo
                INNER JOIN usuario AS u
                    ON c.idusuario = u.idusuario
                INNER JOIN compraitem AS ci
                    ON c.idcompra = ci.idcompra
                INNER JOIN producto AS p
                    ON ci.idproducto = p.idproducto";

        return $sql; 
    }

    public function sqlCompraOne() {
        $sql = $this->sqlComprasAll();

        return "{$sql} WHERE c.idcompra = ?";
    }
}
