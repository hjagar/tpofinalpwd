<?php

namespace PhpMvc\Framework\Data;

use ArrayObject;
use Exception;
use ReflectionClass;
use PhpMvc\Framework\Data\Database;
use PhpMvc\Framework\Data\Constants\ModelAffixes;
use PhpMvc\Framework\Data\Constants\ModelRelations;

abstract class Model
{
    #region Protected Properties
    protected string $table = '';
    protected string | array $primaryKey = 'id';
    protected bool $autoIncrement = true;
    protected array $relations = []; // TODO: Implementar relaciones
    #endregion

    #region Private Properties
    private Database $database;
    private QueryBuilder $queryBuilder;
    private array $attributes = [];
    private array $primaryKeyAttributes = [];
    private bool $isNewRecord = true;
    private Pluralize $pluralize;
    private string $primaryKeyPreffix;
    private string $primaryKeySuffix;
    private array $params = [];
    #endregion

    #region Constructor
    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->pluralize = new Pluralize();
        $this->queryBuilder = new QueryBuilder($this);
        $this->primaryKeyPreffix = env('PRIMARY_KEY_PREFFIX', ModelAffixes::none->value);
        $this->primaryKeySuffix = env('PRIMARY_KEY_SUFFIX', ModelAffixes::none->value);
    }
    #endregion

    #region Magic Methods
    public function __get(string $name)
    {
        $returnValue = null;

        if (array_key_exists($name, $this->attributes)) {
            $returnValue = $this->attributes[$name];
        } elseif (property_exists($this, $name)) {
            $returnValue = $this->$name;
        } elseif (array_key_exists($name, $this->relations)) {
            $returnValue = $this->getRelation($name);
        } else {
            throw new Exception("Property {$name} does not exist in " . static::class);
        }

        return $returnValue;
    }

    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            $this->attributes[$name] = $value;
        }
    }

    public function __call(string $name, array $arguments)
    {
        $returnValue = null;

        if (method_exists($this, $name)) {
            $returnValue = call_user_func_array([$this, $name], $arguments);
        } elseif (array_key_exists($name, $this->relations)) {
            $returnValue = $this->getRelationQueryable($name);
        } else {
            throw new Exception("Method {$name} does not exist in " . static::class);
        }

        return $returnValue;
    }
    #endregion

    #region Static Public Methods
    /**
     * Find a record by its primary key.
     *
     * @param string ...$id The primary key values to search for.
     * @return static|null Returns an instance of the model or null if not found.
     * @example
     * ```php
     * $model = Model::find(123, '456'); // keys in order of primary key definition
     * ``
     */
    public static function find(...$id): ?static
    {
        $instance = new static();
        $queryBuilder = $instance->queryBuilder;
        $params = $instance->prepareParams($id, $instance->getPrimaryKey());
        $query = $queryBuilder
            ->select()
            ->from()
            ->where()
            ->getQuery();
        $data = $instance->database->fetchOne($query, $params);

        return $instance->mapToObject($data);
    }

    /**
     * Find all records in the model's table.
     *
     * @return array Returns an array of model instances.
     */
    public static function all(): array
    {
        $instance = new static();
        $queryBuilder = $instance->queryBuilder;
        $query = $queryBuilder
            ->select()
            ->from()
            ->getQuery();
        $data = $instance->database->fetchAll($query);

        return $instance->mapDataToObjects($data);
    }

    /**
     * Find records based on specified conditions.
     *
     * @param mixed ...$conditions The conditions to filter the records.
     * @return array Returns an array of model instances that match the conditions.
     * @example
     * ```php
     * $models = Model::where(['status' => 'active', 'category' => 'news'])->get();
     * ```
     * This method allows you to specify conditions as key-value pairs, where the key 
     * is the column name and the value is the value to match.
     */
    public static function where($conditions): QueryBuilder
    {
        $instance = new static();
        $queryBuilder = $instance->queryBuilder;
        $instance->params = $instance->prepareParams($conditions);
        $queryBuilder
            ->select()
            ->from()
            ->where($conditions);

        return $queryBuilder;
    }

    /**
     * Eager load relations for the model.
     *
     * @param string ...$relations The relations to load.
     * @return static Returns an instance of the model with loaded relations.
     * @example
     * ```php
     * $model = Model::with('relatedmodel')->get();
     * ```
     */
    public static function with(...$relations): QueryBuilder
    {
        $instance = new static();
        $queryBuilder = $instance->queryBuilder;
        return $queryBuilder
            ->select()
            ->from()
            ->join(...$relations);
    }
    #endregion

    #region Instance Public Methods
    public function get(): array
    {
        [$query, $params] = $this->getQueryAndParams();
        $data = $this->database->fetchAll($query, $params);
        $returnValue = $this->mapDataToObjects($data);

        return $returnValue;
    }

    private function getRelation(string $relationName): ?ArrayObject
    {
        $returnValue = null;

        if (array_key_exists($relationName, $this->relations)) {
            [$relationType, $modelClass] = $this->relations[$relationName];
            $modelInstance = new $modelClass();

            if ($relationType === ModelRelations::HasMany) {
                $returnValue = new ArrayObject($modelInstance->where([$modelInstance->getPrimaryKey()[0] => $this->attributes[$this->getPrimaryKey()[0]]])->get());
            } elseif ($relationType === ModelRelations::BelongsTo) {
                $returnValue = $modelInstance->find($this->attributes[$modelInstance->getPrimaryKey()[0]]);
            }
        }

        return $returnValue;
    }

    private function getRelationQueryable(string $relationName): QueryBuilder
    {
        $returnValue = null;

        if (array_key_exists($relationName, $this->relations)) {
            [$relationType, $modelClass] = $this->relations[$relationName];
            $modelInstance = new $modelClass();

            if ($relationType === ModelRelations::HasMany) {
                $returnValue = $modelInstance->where([$modelInstance->getPrimaryKey()[0] => $this->attributes[$this->getPrimaryKey()[0]]]);
            } elseif ($relationType === ModelRelations::BelongsTo) {
                $returnValue = $modelInstance->where([$modelInstance->getPrimaryKey()[0] => $this->attributes[$modelInstance->getPrimaryKey()[0]]]);
            }
        }

        return $returnValue;
    }

    private function mapDataToObjects(array $data): array
    {
        $returnValue = [];

        if ($data) {
            $returnValue = array_map(fn($row) => $this->mapToObject($row), $data);;
        }

        return $returnValue;
    }

    public function first(): ?static
    {
        $returnValue = null;

        [$query, $params] = $this->getQueryAndParams();
        $data = $this->database->fetchOne($query, $params);

        if ($data) {
            $returnValue = $this->mapToObject($data);
        }

        return $returnValue;
    }

    public function insert(): bool
    {
        $returnValue = false;

        if ($this->isNewRecord) {
            $tableName = $this->getTableName();
            $columns = array_keys($this->attributes);
            $placeholders = $this->makePlaceholders($columns);
            $query = "INSERT INTO {$tableName} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $params = $this->prepareParams($this->attributes);

            if ($returnValue = $this->database->execute($query, $params)) {
                $this->isNewRecord = false;

                if ($this->autoIncrement) {
                    $lastInsertId = $this->database->lastInsertId();
                    $this->attributes[$this->getPrimaryKey()[0]] = $lastInsertId;
                }
                $this->primaryKeyAttributes = array_intersect_key($this->attributes, array_flip($this->getPrimaryKey()));
            }
        } else {
            throw new Exception("Cannot insert a record that is not new.");
        }

        return $returnValue;
    }

    public function update(): bool
    {
        $returnValue = false;

        if (!$this->isNewRecord) {
            $tableName = $this->getTableName();
            $setClauses = [];
            $params = [];

            foreach ($this->attributes as $key => $value) {
                if (!in_array($key, $this->getPrimaryKey())) {
                    $setClauses[] = "{$key} = :{$key}";
                    $params[$key] = $value;
                }
            }

            if (!empty($setClauses)) {
                $where = $this->whereClause();
                $query = "UPDATE {$tableName} SET " . implode(', ', $setClauses) . "{$where}";
                $params = array_merge($params, $this->prepareParams($this->primaryKeyAttributes));

                if ($returnValue = $this->database->execute($query, $params)) {
                    $this->primaryKeyAttributes = array_intersect_key($this->attributes, array_flip($this->getPrimaryKey()));
                }
            }
        } else {
            throw new Exception("Cannot update a record that is new.");
        }

        return $returnValue;
    }

    public function delete(): bool
    {
        $returnValue = false;

        if (!$this->isNewRecord) {
            $tableName = $this->getTableName();
            $where = $this->whereClause();
            $query = "DELETE FROM {$tableName}{$where}";
            $params = $this->prepareParams($this->primaryKeyAttributes);

            if ($returnValue = $this->database->execute($query, $params)) {
                $this->attributes = [];
                $this->primaryKeyAttributes = [];
                $this->isNewRecord = true;
            }
        } else {
            throw new Exception("Cannot delete a record that is new.");
        }

        return $returnValue;
    }

    public function save(): bool
    {
        $returnValue = false;

        if ($this->isNewRecord) {
            $returnValue = $this->insert();
        } else {
            $returnValue = $this->update();
        }

        return $returnValue;
    }

    public function __toString(): string
    {
        return json_encode($this->attributes, JSON_PRETTY_PRINT);
    }

    public function getTableName(): string
    {
        $tableName = $this->table ?: strtolower($this->getModelShortName());

        return $this->pluralize->pluralize($tableName);
    }

    public function getPrimaryKey(): array
    {
        $returnValue = $this->primaryKey;

        if (is_string($this->primaryKey)) {
            $returnValue = ["{$this->getPrimaryKeyPreffix()}{$this->primaryKey}{$this->getPrimaryKeySuffix()}"];
        }

        return $returnValue;
    }
    #endregion

    #region Private Methods
    private function getModelShortName(): string
    {
        return new ReflectionClass($this)->getShortName();
    }

    private function getAffix($affix)
    {
        $returnValue = '';

        if (is_string($affix)) {
            $modelAffix = ModelAffixes::tryFrom($affix);

            if ($modelAffix) {
                switch ($modelAffix) {
                    case ModelAffixes::modelname:
                        $returnValue = strtolower($this->getModelShortName());
                        break;
                    case ModelAffixes::MODELNAME:
                        $returnValue = strtoupper($this->getModelShortName());
                        break;
                    case ModelAffixes::MODEL_NAME:
                        $returnValue = strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->getModelShortName()));
                        break;
                    case ModelAffixes::model_name:
                        $returnValue = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->getModelShortName()));
                        break;
                    case ModelAffixes::modelName:
                        $returnValue = lcfirst($this->getModelShortName());
                        break;
                    case ModelAffixes::MoldeName:
                        $returnValue = $this->getModelShortName();
                        break;
                    default:
                        $returnValue = '';
                }
            }
        }

        return $returnValue;
    }

    private function getPrimaryKeyPreffix(): string
    {
        return $this->getAffix($this->primaryKeyPreffix);
    }

    private function getPrimaryKeySuffix(): string
    {
        return $this->getAffix($this->primaryKeySuffix);
    }

    private function prepareParams(array $params, ?array $paramKeys = null): array
    {
        if ($paramKeys === null) {
            $paramKeys = array_keys($params);
        }

        return array_combine(
            $this->makePlaceholders($paramKeys),
            array_values($params)
        );
    }

    private function mapToObject(array $data): ?static
    {
        $object = null;

        if (!empty($data)) {
            $object = new static();
            $object->attributes = $data;
            $object->primaryKeyAttributes = array_intersect_key($data, array_flip($this->getPrimaryKey()));
            $object->isNewRecord = false;
        }

        return $object;
    }

    private function makePlaceholders(array $columns): array
    {
        return array_map(fn($col) => ":{$col}", $columns);
    }

    private function setQueryAndParams(string $query, array $params): void
    {
        $this->query = $query;
        $this->params = $params;
    }

    private function getQueryAndParams(): array
    {
        return [$this->queryBuilder->getQuery(), $this->params];
    }
    #endregion
}
