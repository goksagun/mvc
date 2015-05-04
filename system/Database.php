<?php namespace App;

use \PDO;

/**
* Database
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Database
{
    /**
     * @var null
     */
    private $driver;

    /**
     * @var null
     */
    private $host;
    /**
     * @var null
     */
    private $user;
    /**
     * @var null
     */
    private $pass;
    /**
     * @var null
     */
    private $dbname;

    /**
     * @var
     */
    private $dbh;
    /**
     * @var
     */
    private $error;

    /**
     * @var
     */
    private $stmt;

    /**
     * @var null
     */
    private $fetch;

    /**
     *
     */
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

    /**
     *
     */
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
            // $this->dbh = new Connection($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * @param $param
     * @param $value
     * @param null $type
     */
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
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * @param $params
     */
    public function bindParams($params)
    {
        if (count($params)) {
            foreach ($params as $param => $value) {
                $param = contains(':', $param) ? $param : ":{$param}";
                $this->bind($param, $value);
            }
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function execute($params = array())
    {
        $this->bindParams($params);

        return $this->stmt->execute();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function resultset(array $params = array())
    {
        $this->execute($params);

        return $this->stmt->fetchAll($this->fetch);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function single(array $params = array())
    {
        $this->execute($params);

        return $this->stmt->fetch($this->fetch);
    }

    /**
     * @return mixed
     */
    public function columnCount()
    {
        return $this->stmt->columnCount();
    }

    /**
     * @return mixed
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @return mixed
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * @return mixed
     */
    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    /**
     * @return mixed
     */
    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    /**
     * @return mixed
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
