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

    private function sqlComprasFrom(): string
    {
        $sql =
            "FROM compra AS c
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

    private function sqlComprasSelectFrom(): string
    {
        $sqlFrom = $this->sqlComprasFrom();
        $sql =
            "SELECT
                c.idcompra,
                u.idusuario,
                ce.idcompraestado,
                cet.idcompraestadotipo,
                DATE_FORMAT(c.fecha, '%d/%m/%Y') AS fecha,
                u.nombre as usuario,
                u.email,
                GROUP_CONCAT(p.nombre SEPARATOR ', ') AS productos,
                cet.nombre AS estado,
                CASE
                    WHEN cet.nombre = 'iniciada' THEN '🛒'
                    WHEN cet.nombre = 'aceptada' THEN '✅'
                    WHEN cet.nombre = 'enviada' THEN '🚚'
                    WHEN cet.nombre = 'cancelada' THEN '❌'		
                END AS estado_emoji,
                CASE
                    WHEN cet.nombre = 'iniciada' THEN 'bg-primary'
                    WHEN cet.nombre = 'aceptada' THEN 'bg-warning'
                    WHEN cet.nombre = 'enviada' THEN 'bg-success'
                    WHEN cet.nombre = 'cancelada' THEN 'bg-danger'		
                END AS estado_badge,
                SUM(p.precio * ci.cantidad) AS total
            {$sqlFrom}";

        return $sql;
    }

    private function sqlComprasGroupBy(): string
    {
        $sql =
            "GROUP BY 
                c.idcompra,
                u.idusuario,
                ce.idcompraestado,
                cet.idcompraestadotipo,
                c.fecha,
                u.nombre,
                u.email";

        return $sql;
    }

    public function sqlComprasAll(): string
    {
        $sql = "{$this->sqlComprasSelectFrom()} {$this->sqlComprasGroupBy()}";

        return $sql;
    }

    public function sqlCompraOne(): string
    {
        $sql = "{$this->sqlComprasSelectFrom()} WHERE c.idcompra = ? {$this->sqlComprasGroupBy()}";;

        return $sql;
    }

    public function sqlMyPurchases(): string
    {
        $sql = "{$this->sqlComprasSelectFrom()} WHERE c.idusuario = ? {$this->sqlComprasGroupBy()}";;

        return $sql;
    }

    public function sqlMyPurchasesDetails(): string
    {
        $sqlFrom = $this->sqlComprasFrom();
        $sql =
            "SELECT
                c.idcompra,
                p.nombre AS producto,
                p.precio, 
                ci.cantidad,
                (p.precio * ci.cantidad) AS total
            {$sqlFrom}
            WHERE c.idcompra = ?";

        return $sql;
    }
}
