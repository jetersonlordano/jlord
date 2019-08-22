<?php

/**
 * Cria uma conexão com o banco de dados
 * Realiza funções CRUD (Create, Read, Update e Delete) usando PDO
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Conn
{
    // Dados para conexão com banco.
    private $host = DBHOST;
    private $db = DBNAME;
    private $user = DBUSER;
    private $pass = DBPASS;

    // Internas
    private $connect;
    private $JPDO;
    private $connection;
    private $queryStr;
    private $values;

    /**
     * Seta configuração alternativa para conexão com banco de dados
     * @param String $host
     * @param String $db
     * @param String $user
     * @param String $pass
     */
    public function setConfig(string $host, string $db, string $user, string $pass)
    {
        $this->host = $host;
        $this->db = $db;
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * Faz uma consulta do banco de dados
     * @param String $fields Campos da a serem consultados - Ex: 'id, name'
     * @param String $table Nome da tabela
     * @param String $terms Condição para a consulta - Ex: 'WHERE id = :id'
     * @param Array  $values Valores das keys passadas nos termos - Ex: ['id' => 1]
     */
    public function select(string $fields, string $table, string $terms = null, array $values = null)
    {
        $this->values = $values;
        $this->queryStr = ("SELECT {$fields} FROM {$table} {$terms}");
    }

    /**
     * Recupera os dados encontrados no banco
     * @return Object
     */
    public function fetchAll()
    {return ($this->JPDO) ? $this->JPDO->fetchAll(PDO::FETCH_ASSOC) : null;}

    /**
     * Inseri dados no banco
     * @param String $table Nome da tabela
     * @param Array $values Valores dos campos - Ex: ['name' => 'Jeterson']
     */
    public function insert(string $table, array $values)
    {
        $fields = implode(", ", array_keys($values));
        $bind = ':' . implode(", :", array_keys($values));
        $this->values = $values;
        $this->queryStr = ("INSERT INTO {$table} ({$fields}) VALUES ({$bind})");
    }

    /**
     * Faz copia de dados de uma tabela para outra
     * @param String $table Nome da tabela
     * @param String $fields Campos da tabela que serão preenchidos - Ex: 'name, email'
     * @param String $fieldsCopy Campos da tabela que serão copiados - Ex: 'name, email'
     * @param String $from Tabela que os dados serão copiados
     * @param String $terms Condição para copia
     */
    public function copy(string $table, string $fields, string $fieldsCopy, string $from, string $terms = null)
    {$this->queryStr = ("INSERT INTO {$table} ({$fields}) SELECT {$fieldsCopy} FROM {$from} {$terms}");}

    /**
     * Atualiza dados no banco
     * @param String $table Nome da tabela
     * @param String $terms Condição de atualização Ex: 'name = :name WHERE id = :id'
     * @param Array $values Valores dos campos - Ex: ['name' => 'Jeterson']
     */
    public function update(string $table, string $terms, array $values)
    {
        $this->values = $values;
        $this->queryStr = ("UPDATE {$table} SET {$terms}");
    }
    /**
     * Deleta dados do banco
     * @param String $table Nome da table
     * @param String $terms Codição para deletar - Ex: 'WHERE id = :id'
     * @param String $values Valores das keys passadas nos termos - Ex: ['id' => 1]
     */
    public function delete(string $table, string $terms, array $values)
    {
        $this->values = $values;
        $this->queryStr = ("DELETE FROM {$table} {$terms}");
    }

    /**
     * Inicia a conexão com banco de dados
     * @return Object Objeto PDO
     */
    private function getConn()
    {
        if (!$this->connection) {
            try {
                $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
                $PDOOptions = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                $this->connection = new PDO($dsn, $this->user, $this->pass, $PDOOptions);
            } catch (PDOException $erro) {PHPNOTIFY($erro->getMessage());die;}
        }
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->connection;
    }

    /**
     * Executa as ações da classe
     * @return Boolean
     */
    public function exec()
    {
        try {
            $this->connect = $this->getConn();
            $this->JPDO = $this->connect->prepare($this->queryStr);
            if ($this->values) {$this->prepare();}
            return $this->JPDO->execute();
        } catch (PDOException $erro) {PHPNOTIFY($erro->getMessage());die;}
    }

    // Prepara os dados com paramentros seguros
    private function prepare()
    {foreach ($this->values as $key => $vlr) {$this->JPDO->bindParam(":" . $key, $this->values[$key], PDO::PARAM_STR);}}
}
