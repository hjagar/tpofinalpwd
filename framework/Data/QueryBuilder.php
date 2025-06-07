<?php

namespace PhpMvc\Framework\Data;

use PhpMvc\Framework\Data\Constants\QueryBuilderIndexes;
use PhpMvc\Framework\Data\Constants\SelectQueryIndexes;

class QueryBuilder
{
    private array $query;

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
            QueryBuilderIndexes::SqlStatement => array_fill(0, 6, ''),
            QueryBuilderIndexes::WhereConditions => [],
            QueryBuilderIndexes::JoinClauses => [],
            QueryBuilderIndexes::OrderByClauses => []
        ];
    }

    #region Magic Methods
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

        $this->setSqlStatement(SelectQueryIndexes::Select, "SELECT {$columns}");

        return $this;
    }

    public function from(): QueryBuilder
    {
        $this->setSqlStatement(SelectQueryIndexes::From, "FROM {$this->getTableName()}");

        return $this;
    }

    public function where(?array $conditions = null): QueryBuilder
    {
        if ($conditions === null) {
            $conditions = $this->getPrimaryKey();
        }

        $whereClauses = array_map(fn($key) => "{$key} = :{$key}", array_keys($conditions));
        $whereConditions = $this->query[QueryBuilderIndexes::WhereConditions];
        $whereConditions = array_unique(array_merge($whereConditions, $whereClauses));
        $this->query[QueryBuilderIndexes::WhereConditions] = $whereConditions;

        return $this;
    }

    public function orderBy($column, $direction = 'ASC'): QueryBuilder
    {
        $orderByClauses = $this->query[QueryBuilderIndexes::OrderByClauses];
        $orderByClauses = array_unique(array_merge($orderByClauses, ["{$column} {$direction}"]));
        $this->query[QueryBuilderIndexes::OrderByClauses] = $orderByClauses;

        return $this;
    }

    public function limit($limit, $offset = 0): QueryBuilder
    {
        $this->setSqlStatement(SelectQueryIndexes::Limit, "LIMIT {$offset}, {$limit}");

        return $this;
    }

    public function getQuery(): string
    {
        $whereConditions = $this->query[QueryBuilderIndexes::WhereConditions];

        if (!empty($whereConditions)) {
            $this->setSqlStatement(SelectQueryIndexes::Where, 'WHERE ' . implode(' AND ', $whereConditions));
        }

        $joinClauses = $this->query[QueryBuilderIndexes::JoinClauses];

        if (!empty($joinClauses)) {
            $this->setSqlStatement(SelectQueryIndexes::Join, implode(' ', $joinClauses));
        }

        $orderClauses = $this->query[QueryBuilderIndexes::OrderByClauses];

        if (!empty($orderClauses)) {
            $this->setSqlStatement(SelectQueryIndexes::OrderBy, implode(', ', $joinClauses));
        }

        $query = implode(' ', $this->query);
        $query = trim($query);

        return $query;
    }

    public function join($relations): QueryBuilder
    {
        $modelRelations = $this->model->relations;
        $currentRelations = array_intersect(array_keys($modelRelations), $relations);
        $joinClauses = [];

        foreach ($currentRelations as $relation) {
            [$relationType] = $modelRelations[$relation];
            $methodName = lcfirst($relationType);
            $joinClauses[] = $this->$methodName($modelRelations[$relation]);
        }

        if (!empty($joinClauses)) {
            $queryJoinClauses = $this->query[QueryBuilderIndexes::JoinClauses];
            $this->query[QueryBuilderIndexes::JoinClauses] = array_unique(array_merge($queryJoinClauses, $joinClauses));
        }

        return $this;
    }
    #endregion

    #region Private Methods
    private function setSqlStatement($clause, $value)
    {
        $this->query[QueryBuilderIndexes::SqlStatement][$clause] = $value;
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
