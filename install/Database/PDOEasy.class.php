<?php

/**
 * Class PHPEasy
 * @author Jeterson Lordano <https://github.com/jetersonlordano>
 * @package Database
 */

namespace Database;

use PDO;
use PDOException;

class PDOEasy
{

    /** @var string */
    public $query;

    /**  @var array */
    public $params;

    /** @var object */
    private $statement;

    /**  @var object */
    private $connection;

    /** @var string */
    private $error;

    /**
     * SQL select
     */
    public function select(string $table, string $fields = '*', string $terms = null, array $params = null)
    {
        $this->params = $params;
        $this->query = "SELECT {$fields} FROM {$table} {$terms}";
    }

    /**
     * SQL insert
     */
    public function insert(string $table, array $params)
    {
        $fields = implode(", ", array_keys($params));
        $bind = ':' . implode(", :", array_keys($params));
        $this->params = $params;
        $this->query = "INSERT INTO {$table} ({$fields}) values ({$bind})";
    }

    /**
     * SQL update
     */
    public function update(string $table, string $terms, array $params)
    {
        $this->params = $params;
        $this->query = "UPDATE {$table} SET {$terms}";
    }

    /**
     * SQL delete
     */
    public function delete(string $table, string $terms, array $params)
    {
        $this->params = $params;
        $this->query = "DELETE FROM {$table} {$terms}";
    }

    /**
     * SQL Copy from one table to another
     */
    public function copy(string $table, string $fields, string $fieldsSelect, string $tableSelect, string $terms = null)
    {
        $this->query = "INSERT INTO {$table} ({$fields}) SELECT {$fieldsSelect} FROM {$tableSelect} {$terms}";
    }

    /**
     * @return void
     */
    public function fetchAll()
    {
        return $this->statement->fetchAll();
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * Replace prefixes
     */
    public function prefixes(array $pfx)
    {
        foreach ($pfx as $key => $value) {
            $this->query = str_replace($key, $value, $this->query);
        }
    }

    /**
     * @return bool
     */
    public function exec()
    {
        $this->connection = $this->connection ?? $this->connect();

        if (!$this->query) {
            return $this->connection;
        }

        $this->statement = $this->connection->prepare($this->query);
        
        foreach ($this->params ?? [] as $key => $vl) {
            $this->statement->bindParam(":" . $key, $this->params[$key], PDO::PARAM_STR);
        }

        return $this->statement->execute();
    }

    /**
     * @return PDOException|null
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return PDO||null
     */
    private function connect()
    {
        try {

            $PDO = new PDO(
                'mysql:host=' . DBCONFIG['host'] . ';dbname=' . DBCONFIG['db'] . ';',
                DBCONFIG['user'],
                DBCONFIG['psw'],
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL,
                ]
            );

        } catch (PDOException $exception) {
            $this->error = $exception;
            return null;
        }

        return $PDO;
    }
}
