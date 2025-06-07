<?php

namespace PhpMvc\Framework\Data;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

/**
 * Database class for handling database connections and queries.
 */
class Database
{
    private PDO $connection;
    private PDOStatement $statement;
    private string $dsn;
    private string $username;
    private string $password;
    private array $options;
    private static ?Database $instance = null;

    /**
     * Get the singleton instance of the Database class.
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $dbConnection = env('DB_CONNECTION', 'mysql');
        $dbHost = env('DB_HOST', 'localhost');
        $dbPort = env('DB_PORT', '3306');
        $dbName = env('DB_DATABASE', 'php_mvc');
        $this->username = env('DB_USERNAME', 'root');
        $this->password = env('DB_PASSWORD', '');
        $this->dsn = "{$dbConnection}:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    private function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    private function __set($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }

    public function connect(): bool
    {
        $returnValue = false;
        try {
            $this->connection = new PDO($this->dsn, $this->username, $this->password, $this->options);
            $returnValue = true;
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: {$e->getMessage()}");
        }

        return $returnValue;
    }

    public function execute($query, $params = []): bool
    {
        $returnValue = false;

        if (empty($query)) {
            throw new RuntimeException("Query cannot be empty.");
        }

        try {
            if ($this->connect()) {
                $this->statement = $this->connection->prepare($query);
                $returnValue = $this->statement->execute($params);
            }
        } catch (PDOException $e) {
            throw new RuntimeException("Error executing query: {$e->getMessage()}");
        }

        return $returnValue;
    }

    public function fetchAll($query, $params = []): array
    {
        $returnValue = [];

        try {
            $executeResult = $this->execute($query, $params);

            if ($executeResult) {
                $returnValue = $this->statement->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            throw new RuntimeException("Error fetching data: {$e->getMessage()}");
        }

        return $returnValue;
    }

    public function fetchOne($query, $params = []): ?array
    {
        $returnValue = null;

        try {
            $executeResult = $this->execute($query, $params);

            if ($executeResult) {
                $returnValue = $this->statement->fetch(PDO::FETCH_ASSOC) ?: null;
            }
        } catch (PDOException $e) {
            throw new RuntimeException("Error fetching data: {$e->getMessage()}");
        }

        return $returnValue;
    }

    public function lastInsertId(): string
    {
        if ($this->connection) {
            return $this->connection->lastInsertId();
        }
        throw new RuntimeException("No active database connection.");
    }
}
