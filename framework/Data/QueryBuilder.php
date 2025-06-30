<?php

namespace PhpMvc\Framework\Data;

use PhpMvc\Framework\Concerns\HasSplitKey;
use PhpMvc\Framework\Data\Constants\OrderDirectionType;
use PhpMvc\Framework\Data\Constants\QueryBuilderIndexType;
use PhpMvc\Framework\Data\Constants\SelectQueryIndexType;

class QueryBuilder
{
    private array $query;

    use HasSplitKey;

    /**
     * El constructor de QueryBuilder crea una propiedad privada $query donde el primer elemento
     * son las clausulas de la sentencia SQL, y se detallan de esta forma:
     *      0: SELECT clause
     *      1: FROM clause
     *      2: JOIN clauses
     *      3: WHERE clause
     *      4: ORDER BY clause
     *      5: LIMIT clause
     * Además cuenta con dos arreglos destinados a las clausulas where y otro destinado a las 
     * clausulas join.
     */
    public function __construct(private Model $model)
    {
        $this->query = [
            QueryBuilderIndexType::SqlStatement => array_fill(0, 6, ''),
            QueryBuilderIndexType::WhereConditions => [],
            QueryBuilderIndexType::JoinClauses => [],
            QueryBuilderIndexType::OrderByClauses => []
        ];
    }

    #region Magic Methods
    /**
     * Permite acceder a propiedades del modelo o de la clase QueryBuilder.
     * Si la propiedad existe en la clase QueryBuilder, la devuelve directamente.
     * Si la propiedad existe en el modelo, la devuelve a través del modelo.
     * Si la propiedad no existe en ninguno de los dos, lanza una excepción.
     */
    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->{$name};
        } elseif (property_exists($this->model, $name)) {
            $returnValue = $this->model->{$name};
        } else {
            throw new \Exception("Property {$name} does not exist.");
        }

        return $returnValue;
    }

    /**
     * Permite llamar a métodos que no están definidos en la clase QueryBuilder o en el modelo.
     * Si el método existe en la clase QueryBuilder, lo llama directamente.
     * Si el método existe en el modelo, lo llama a través del modelo.
     * Si el método no existe en ninguno de los dos, lanza una excepción.
     */
    public function __call($name, $arguments)
    {
        $returnValue = null;

        if (method_exists($this, $name)) {
            $returnValue = $this->{$name}(...$arguments);
        } elseif (method_exists($this->model, $name)) {
            $returnValue = $this->model->{$name}(...$arguments);
        } else {
            throw new \Exception("Method {$name} does not exist.");
        }

        return $returnValue;
    }
    #endregion

    #region Public Methods
    public function select($columns = '*'): QueryBuilder
    {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }

        $this->setSqlStatement(SelectQueryIndexType::Select, "SELECT {$columns}");

        return $this;
    }

    public function from(): QueryBuilder
    {
        $this->setSqlStatement(SelectQueryIndexType::From, "FROM {$this->getTableName()}");

        return $this;
    }

    public function where(array $conditions): QueryBuilder
    {
        $whereClauses = array_map(fn($key) => "{$key} = :{$this->splitKey($key)}", array_keys($conditions));
        $whereConditions = $this->query[QueryBuilderIndexType::WhereConditions];
        $whereConditions = array_unique(array_merge($whereConditions, $whereClauses));
        $this->query[QueryBuilderIndexType::WhereConditions] = $whereConditions;
        $this->model->prepareParams($conditions);

        return $this;
    }

    public function orderBy($column, $direction = OrderDirectionType::ASC): QueryBuilder
    {
        $orderByClauses = $this->query[QueryBuilderIndexType::OrderByClauses];
        $orderByClauses = array_unique(array_merge($orderByClauses, ["{$column} {$direction}"]));
        $this->query[QueryBuilderIndexType::OrderByClauses] = $orderByClauses;

        return $this;
    }

    public function limit($limit, $offset = 0): QueryBuilder
    {
        $this->setSqlStatement(SelectQueryIndexType::Limit, "LIMIT {$offset}, {$limit}");

        return $this;
    }

    public function getQuery(): string
    {
        $whereConditions = $this->query[QueryBuilderIndexType::WhereConditions];

        if (!empty($whereConditions)) {
            $this->setSqlStatement(SelectQueryIndexType::Where, 'WHERE ' . implode(' AND ', $whereConditions));
        }

        $joinClauses = $this->query[QueryBuilderIndexType::JoinClauses];

        if (!empty($joinClauses)) {
            $this->setSqlStatement(SelectQueryIndexType::Join, implode(' ', $joinClauses));
        }

        $orderClauses = $this->query[QueryBuilderIndexType::OrderByClauses];

        if (!empty($orderClauses)) {
            $this->setSqlStatement(SelectQueryIndexType::OrderBy, "ORDER BY " . implode(', ', $orderClauses));
        }

        $query = trim(implode(' ', $this->query['SqlStatement']));

        return $query;
    }

    public function join($relations): QueryBuilder
    {
        $modelRelations = $this->model->relations;

        if (is_string($relations)) {
            $relations = [$relations];
        }

        $currentRelations = array_intersect(array_keys($modelRelations), $relations);
        $joinClauses = [];

        foreach ($currentRelations as $relation) {
            [$relationType] = $modelRelations[$relation];
            $methodName = lcfirst($relationType);
            $joinClauses = array_merge($joinClauses, $this->$methodName($modelRelations[$relation]));
        }

        if (!empty($joinClauses)) {
            $queryJoinClauses = $this->query[QueryBuilderIndexType::JoinClauses];
            $this->query[QueryBuilderIndexType::JoinClauses] = array_unique(array_merge($queryJoinClauses, $joinClauses));
        }

        return $this;
    }
    #endregion

    #region Private Methods
    private function setSqlStatement($clause, $value)
    {
        $this->query[QueryBuilderIndexType::SqlStatement][$clause] = $value;
    }

    private function createForeignObject(array $relation): Model
    {
        [, $className] = $relation;
        if (!class_exists($className)) {
            throw new \Exception("Class {$className} does not exist.");
        }

        return new $className();
    }

    private function createJoinClause($localTable, $foreignTable, $localKey, $foreignKey): string
    {
        return "LEFT JOIN {$foreignTable} ON {$foreignTable}.{$foreignKey} = {$localTable}.{$localKey}";
    }

    private function belongsTo(array $relation): array
    {
        [,, $localKey, $foreignKey] = array_pad($relation, 4, null);
        $foreignObject = $this->createForeignObject($relation);
        $localKey = $localKey ?? $foreignObject->getPrimaryKey()[0];
        $foreignKey = $foreignKey ?? $foreignObject->getPrimaryKey()[0];

        return [
            $this->createJoinClause(
                $this->model->getTableName(),
                $foreignObject->getTableName(),
                $localKey,
                $foreignKey
            )
        ];
    }

    private function hasMany(array $relation): array
    {
        [,, $localKey, $foreignKey] = array_pad($relation, 4, null);
        $foreignObject = $this->createForeignObject($relation);
        $localKey = $localKey ?? $this->model->getPrimaryKey()[0];
        $foreignKey = $foreignKey ?? $this->model->getPrimaryKey()[0];

        return [
            $this->createJoinClause(
                $this->model->getTableName(),
                $foreignObject->getTableName(),
                $localKey,
                $foreignKey
            )
        ];
    }

    private function belongsToMany(array $relation): array
    {
        $joinClauses = [];
        [,, $pivotTable, $localKey, $foreignKey] = array_pad($relation, 5, null);
        $foreignObject = $this->createForeignObject($relation);
        $localKey = $localKey ?? $this->model->getPrimaryKey()[0];
        $foreignKey = $foreignKey ?? $foreignObject->getPrimaryKey()[0];
        $joinClauses[] = $this->createJoinClause(
            $this->model->getTableName(),
            $pivotTable,
            $localKey,
            $localKey
        );
        $joinClauses[] = $this->createJoinClause(
            $pivotTable,
            $foreignObject->getTableName(),
            $foreignKey,
            $foreignKey
        );

        return $joinClauses;
    }
}
