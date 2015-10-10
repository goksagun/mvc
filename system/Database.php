<?php namespace App;

use \PDO;

/**
* Database
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Database extends PDO
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

        $this->options  = array();

        $this->fetch    = config_get('database.fetch');

        // Set DSN
        $dsn = $this->setDsn();

        parent::__construct($dsn, $this->user, $this->pass, $this->options);
    }

    /**
     *
     */
    public function setDsn()
    {
        // Set DSN
        $dsn = $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $this->options = array(
            PDO::ATTR_PERSISTENT            => true,
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES      => false,
            PDO::ATTR_STRINGIFY_FETCHES     => false

        );

        // Set mysql connection options
        if ($this->driver == 'mysql') {
            $this->options = array_merge($this->options, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".config_get('database.connections.mysql.charset')
            ));
        }

        return $dsn;
    }

    /**
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->prepare($query);
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
        return (int) $this->stmt->columnCount();
    }

    /**
     * @return mixed
     */
    public function rowCount()
    {
        return (int) $this->stmt->rowCount();
    }

    /**
     * @return mixed
     */
    public function lastInsertId()
    {
        return (int) $this->lastInsertId();
    }

    /**
     * @return mixed
     */
    public function beginTransaction()
    {
        return $this->beginTransaction();
    }

    /**
     * @return mixed
     */
    public function endTransaction()
    {
        return $this->commit();
    }

    /**
     * @return mixed
     */
    public function cancelTransaction()
    {
        return $this->rollBack();
    }

    /**
     * @return mixed
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
