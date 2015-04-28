<?php namespace App;

use \PDO;

/**
* Database
*
*/
class Database
{
    private $driver;

    private $host;
    private $user;
    private $pass;
    private $dbname;

    private $dbh;
    private $error;

    private $stmt;

    private $fetch;

    public function __construct()
    {
        // Set Config
        $this->driver   = $driver = config_get('database.default');

        $this->host     = config_get("database.connections.$driver.host");
        $this->dbname   = config_get("database.connections.$driver.database");
        $this->user     = config_get("database.connections.$driver.username");
        $this->pass     = config_get("database.connections.$driver.password");

        $this->fetch    = config_get('database.fetch');

        $this->connection();
    }

    public function connection()
    {
        // Set DSN
        $dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT            => true,
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION
        );

        // Set mysql connection options
        if ($this->driver == 'mysql') {
            $options = array_merge($options, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".config_get('database.connections.mysql.charset')
            ));
        }

        // Create a new PDO instanace
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        // $this->stmt->bindParam($param, $value, $type);
        $this->stmt->bindValue($param, $value, $type);
    }

    public function bindParams($params)
    {
        if (count($params)) {
            foreach ($params as $param => $value) {
                $param = contains(':', $param) ? $param : ":{$param}";
                $this->bind($param, $value);
            }
        }
    }

    public function execute($params = array())
    {
        $this->bindParams($params);

        return $this->stmt->execute();
    }

    public function resultset(array $params = array())
    {
        $this->execute($params);

        return $this->stmt->fetchAll($this->fetch);
    }

    public function single(array $params = array())
    {
        $this->execute($params);

        return $this->stmt->fetch($this->fetch);
    }

    public function columnCount()
    {
        return $this->stmt->columnCount();
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
