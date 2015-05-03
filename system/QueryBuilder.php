<?php namespace App;

/**
* QueryBuilder
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class QueryBuilder extends Database
{
    private static $instance = null;

    public static $table;

    public $query = '';

    public $selects = array('*');

    public $wheres = array();

    public $params = array();

    public $order = array();

    public $limit = 1000;

    public $offset = 0;

    public $result;

    public static function table($table = '')
    {
        if (self::$instance === null)
        {
            self::$instance = new self;
        }

        self::$table = $table;

        return self::$instance;
    }

    public function select($columns = array('*'))
    {
        $this->selects = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    public function where($key, $operator = '=', $value, $comperator = null)
    {
        if (is_null($comperator)) {
            $comperator = count($this->wheres) ? 'AND' : 'WHERE';
        }

        $this->wheres[] = array(
            'comperator' => $comperator,
            'key' => $key,
            'operator' => $operator,
            'value' => $value,
        );

        return $this;
    }

    public function orWhere($key, $operator = '=', $value)
    {
        $this->where($key, $operator = '=', $value, $comperator = 'OR');

        return $this;
    }

    public function whereIn($key, $operator = '=', $value)
    {
        $this->where($key, $operator = '=', $value, $comperator = 'IN');

        return $this;
    }

    public function get($columns = array('*'))
    {
        $this->executeQuery($columns);

        // return $this->params;

        return $this->resultset($this->params);
    }

    public function first($columns = array('*'))
    {
        $this->executeQuery($columns);

        return $this->single($this->params);
    }

    public function find($id = 0, $key = 'id')
    {
        $this->where($key, $operator = '=', $id);

        return $this->first();
    }

    public function toSql()
    {
        $this->executeQuery();

        return $this->query;
    }

    public function orderBy($key = '', $order = 'ASC')
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at ORDER BY id=DESC LIMIT 1 OFFSET 0'
        $this->order = array(
            'key' => $key,
            'order' => $order
        );

        return $this;
    }

    public function take($limit = 1000)
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at LIMIT 1 OFFSET 0'
        $this->limit = $limit;

        return $this;
    }

    public function skip($offset = 0)
    {
        $this->offset = $offset;

        return $this;
    }

    public function insert(array $data = array())
    {
        $table = self::$table;

        $this->setQuery("INSERT INTO `{$table}`");

        // return is_multi_array($data);

        $columns = implode(', ', array_map(function ($key) {
            return "`{$key}`";
        }, array_keys($data)));

        $this->setQuery(" ({$columns})");

        $values = implode(', ', array_map(function ($key) {
            return ":{$key}";
        }, array_keys($data)));

        $this->setQuery(" VALUES ({$values})");

        // return $data;

        // 'INSERT INTO users (email, password, created_at, updated_at) VALUES (:email, :password, :created_at, :updated_at)'

        return $this->query;

        $this->query($this->query);

        $this->setParams($data);

        $this->execute($this->params);

        return $this->lastInsertId();

        return $this->rowCount();
    }

    public function update(array $data = array(), $id = 0, $key = 'id', $operator = '=')
    {
        // 'UPDATE users SET email=:email, password=:password, created_at=:created_at, updated_at=:updated_at WHERE id=:id'
        $table = self::$table;

        $this->setQuery("UPDATE `{$table}`");

        $columns = implode(', ', array_map(function ($key) {
            return "`{$key}`=:{$key}";
        }, array_keys($data)));

        // return $data;

        $this->setQuery(" SET {$columns}");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        $this->setParams($data);

        return $this->execute($this->params);
    }

    public function delete($id = 0, $key = 'id', $operator = '=')
    {
        // DELETE FROM users WHERE id=:id
        $table = self::$table;

        $this->setQuery("DELETE FROM `{$table}`");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        // return $this->params;
        // return $this->query;
        $this->execute($this->params);

        return $this->rowCount();
    }

    public function executeQuery($columns = array())
    {
        $this->setSelect($this->selects);

        $this->setFrom(self::$table);

        $this->setWhere($this->wheres);

        $this->setOrderBy($this->order);

        $this->setLimit($this->limit);

        $this->setOffset($this->offset);

        // dd($this->query);

        $this->query($this->query);
    }

    public function setSelect($columns)
    {
        $columns = implode(', ', $columns);

        $this->setQuery("SELECT $columns");

        return $this;
    }

    public function setFrom($table = '')
    {
        $this->setQuery(" FROM $table");
    }

    public function setWhere($wheres = array())
    {
        if (count($wheres)) {
            $where = '';
            foreach ($wheres as $array) {
                $where .= " {$array['comperator']} `{$array['key']}`{$array['operator']}:{$array['key']}";

                $params[$array['key']] = $array['value'];
            }

            $this->setQuery($where);

            $this->setParams($params);
        }
    }

    public function setOrderBy($order = array())
    {
        if (count($order)) {
            $order['order'] = strtoupper($order['order']);

            $this->setQuery(" ORDER BY {$order['key']} {$order['order']}");
        }
    }

    public function setLimit($limit = 1000)
    {
        $this->setQuery(" LIMIT $limit");
    }

    public function setOffset($offset = 0)
    {
        $this->setQuery(" OFFSET $offset");
    }

    public function setQuery($query='')
    {
        $this->query .= $query;
    }

    public function setParams($param='')
    {
        $this->params = array_merge($this->params, $param);
    }
}

/**
* DB
*/
class DB extends QueryBuilder
{

    // function __construct()
    // {
    //     parent::__construct();
    // }
}
