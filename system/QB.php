<?php namespace App;

/**
* QB
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class QB extends Database
{
    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $select = array('*');

    /**
     * @var array
     */
    protected $wheres = array();

    /**
     * @var array
     */
    protected $order = array();

    /**
     * @var int
     */
    protected $limit = 1000;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param string $table
     * @return QB
     */
    public static function table($table = '')
    {
        $qb = new static;

        $qb->table = $table;

        return $qb;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function select($columns = array('*'))
    {
        $this->select = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * @param array $select
     */
    private function setSelect($select = array())
    {
        $columns = implode(', ', array_map(function ($column)
        {
            return ($column == '*') ? "{$column}" : "`{$column}`";
        }, $select));

        $this->setQuery("SELECT {$columns}");
    }

    /**
     * @param string $table
     * @return QB
     */
    public function from($table = '')
    {
        return $this->table($table);
    }

    /**
     * @param $table
     */
    private function setFrom($table)
    {
        $this->setQuery(" FROM `{$table}`");
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param null $comperator
     * @return $this
     */
    public function where($column = '', $operator = '=', $value = '', $comperator = null)
    {
        if (is_null($comperator)) {
            $comperator = count($this->wheres) ? 'AND' : 'WHERE';
        }

        $this->wheres[] = array(
            'comperator' => $comperator,
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        );

        return $this;
    }

    /**
     * @param $key
     * @param string $operator
     * @param $value
     * @return $this
     */
    public function orWhere($key, $operator = '=', $value)
    {
        $this->where($key, $operator = '=', $value, $comperator = 'OR');

        return $this;
    }

    /**
     * @param array $wheres
     */
    private function setWhere($wheres = array())
    {
        if (count($wheres)) {
            $where = '';
            foreach ($wheres as $array) {
                $where .= " {$array['comperator']} `{$array['column']}`{$array['operator']}:{$array['column']}";

                $params[$array['column']] = $array['value'];
            }

            $this->setQuery($where);

            $this->setParams($params);
        }
    }

    /**
     * @param string $key
     * @param string $order
     * @return $this
     */
    public function orderBy($key = '', $order = 'ASC')
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at ORDER BY id=DESC LIMIT 1 OFFSET 0'
        $this->order = array(
            'key' => $key,
            'order' => $order
        );

        return $this;
    }

    /**
     * @param array $order
     */
    private function setOrderBy($order = array())
    {
        if (count($order)) {
            $order['order'] = strtoupper($order['order']);

            $this->setQuery(" ORDER BY `{$order['key']}` {$order['order']}");
        }
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function take($limit = 1000)
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at LIMIT 1 OFFSET 0'
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit($limit = 1000)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $limit
     */
    private function setLimit($limit = 1000)
    {
        $this->setQuery(" LIMIT $limit");
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function skip($offset = 0)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset($offset = 0)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @param int $offset
     */
    private function setOffset($offset = 0)
    {
        $this->setQuery(" OFFSET $offset");
    }

    /**
     * @param string $query
     * @param bool $append
     */
    public function setQuery($query = '', $append = true)
    {
        if ($append) {
            $this->query .= $query;
        } else {
            $this->query = $query;
        }
    }

    /**
     * @param string $param
     */
    private function setParams($param='')
    {
        $this->params = array_merge($this->params, $param);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function get($columns = array())
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->executeQuery($columns);

        return $this->resultset($this->params);
    }

    public function all($columns = array())
    {
        return $this->get($columns);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function first($columns = array())
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->executeQuery($columns);

        return $this->single($this->params);
    }

    /**
     * @param int $id
     * @param string $key
     * @return mixed
     */
    public function find($id = 0, $key = 'id')
    {
        $this->where($key, $operator = '=', $id);

        return $this->first();
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public function insert(array $data = array())
    {
        $table = $this->table;

        $this->setQuery("INSERT INTO `{$table}`");

        if (is_multi_array($data)) {
            $columns = implode(', ', array_map(function ($column) {
                return "`{$column}`";
            }, array_keys(current($data))));

            foreach ($data as $key => $item) {
                $values[] = implode(', ', array_map(function ($column) use ($key) {
                    return ":{$column}_{$key}";
                }, array_keys($item)));
            }

            $this->setQuery(" ({$columns})");

            $values = implode(', ', array_map(function ($value) {
                return "({$value})";
            }, $values));

            $this->setQuery(" VALUES {$values}");

            foreach ($data as $key => $item) {
                foreach ($item as $column => $value) {
                    $param["{$column}_{$key}"] = $value;
                }
            }

            $this->setParams($param);
        } else {
            $columns = implode(', ', array_map(function ($column) {
                return "`{$column}`";
            }, array_keys($data)));

            $this->setQuery(" ({$columns})");

            $values = implode(', ', array_map(function ($column) {
                return ":{$column}";
            }, array_keys($data)));

            $this->setQuery(" VALUES ({$values})");

            $this->setParams($data);
        }

        $this->query($this->query);

        $this->execute($this->params);

        return ($this->rowCount() > 1) ? $this->rowCount() : $this->lastInsertId();
    }

    /**
     * @param array $data
     * @param int $id
     * @param string $key
     * @param string $operator
     * @return mixed
     */
    public function update(array $data = array(), $id = 0, $key = 'id', $operator = '=')
    {
        $table = $this->table;

        $this->setQuery("UPDATE `{$table}`");

        $columns = implode(', ', array_map(function ($column) {
            return "`{$column}`=:{$column}";
        }, array_keys($data)));

        $this->setQuery(" SET {$columns}");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        $this->setParams($data);

        $result = $this->execute($this->params);

        return ($this->rowCount()) > 1 ? $this->rowCount() : $result;
    }

    /**
     * @param int $id
     * @param string $key
     * @param string $operator
     * @return mixed
     */
    public function delete($id = 0, $key = 'id', $operator = '=')
    {
        $table = $this->table;

        $this->setQuery("DELETE FROM `{$table}`");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        $result = $this->execute($this->params);

        return ($this->rowCount()) > 1 ? $this->rowCount() : $result;
    }

    /**
     * @return string
     */
    public function toSql()
    {
        $this->executeQuery();

        return $this->query;
    }

    /**
     * @param array $columns
     */
    private function executeQuery($columns = array())
    {
        if (array_search('*', $columns) == false && count($columns) > 0) { // if not return boolean false
            $this->select($columns);
        }

        $this->setSelect($this->select);
        $this->setFrom($this->table);
        $this->setWhere($this->wheres);
        $this->setOrderBy($this->order);
        $this->setLimit($this->limit);
        $this->setOffset($this->offset);

        $this->query($this->query);
    }
}
