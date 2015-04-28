<?php namespace App;

/**
 * QB
 */
class QB extends Database
{
    protected $query = '';

    protected $table;

    protected $select = array('*');

    protected $wheres = array();

    protected $order = array();

    protected $limit = 1000;

    protected $offset = 0;

    protected $params = array();

    function __construct($table = '')
    {
        parent::__construct();

        $this->table = $table;
    }

    public static function table($table = '')
    {
        return new QB($table);
    }

    public function select($columns = array('*'))
    {
        $this->select = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    private function setSelect($select = array())
    {
        $columns = implode(', ', array_map(function ($column)
        {
            return ($column == '*') ? "{$column}" : "`{$column}`";
        }, $select));

        $this->setQuery("SELECT {$columns}");
    }

    public function from($table = '')
    {
        return static::table($table);
    }

    private function setFrom($table)
    {
        $this->setQuery(" FROM `{$table}`");
    }

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

    public function orWhere($key, $operator = '=', $value)
    {
        $this->where($key, $operator = '=', $value, $comperator = 'OR');

        return $this;
    }

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

    public function orderBy($key = '', $order = 'ASC')
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at ORDER BY id=DESC LIMIT 1 OFFSET 0'
        $this->order = array(
            'key' => $key,
            'order' => $order
        );

        return $this;
    }

    private function setOrderBy($order = array())
    {
        if (count($order)) {
            $order['order'] = strtoupper($order['order']);

            $this->setQuery(" ORDER BY `{$order['key']}` {$order['order']}");
        }
    }

    public function take($limit = 1000)
    {
        // 'SELECT * FROM users WHERE email=:email OR created_at=:created_at LIMIT 1 OFFSET 0'
        $this->limit = $limit;

        return $this;
    }

    public function limit($limit = 1000)
    {
        $this->take($limit);
    }

    private function setLimit($limit = 1000)
    {
        $this->setQuery(" LIMIT $limit");
    }

    public function skip($offset = 0)
    {
        $this->offset = $offset;

        return $this;
    }

    public function offset($offset = 0)
    {
        return $this->skip($offset);
    }

    private function setOffset($offset = 0)
    {
        $this->setQuery(" OFFSET $offset");
    }

    public function setQuery($query = '', $append = true)
    {
        if ($append) {
            $this->query .= $query;
        } else {
            $this->query = $query;
        }
    }

    private function setParams($param='')
    {
        $this->params = array_merge($this->params, $param);
    }

    public function get($columns = array())
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->executeQuery($columns);

        return $this->resultset($this->params);
    }

    public function first($columns = array())
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->executeQuery($columns);

        return $this->single($this->params);
    }

    public function find($id = 0, $key = 'id')
    {
        $this->where($key, $operator = '=', $id);

        return $this->first();
    }

    public function insert(array $data = array())
    {
        $this->setQuery("INSERT INTO `{$this->table}`");

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

        return (is_multi_array($data)) ? $this->rowCount() : $this->lastInsertId();
    }

    public function update(array $data = array(), $id = 0, $key = 'id', $operator = '=')
    {
        $this->setQuery("UPDATE `{$this->table}`");

        $columns = implode(', ', array_map(function ($column) {
            return "`{$column}`=:{$column}";
        }, array_keys($data)));

        $this->setQuery(" SET {$columns}");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        $this->setParams($data);

        return $this->execute($this->params);
    }

    public function delete($id = 0, $key = 'id', $operator = '=')
    {
        $this->setQuery("DELETE FROM `{$this->table}`");

        $this->where($key, $operator, $id);

        $this->setWhere($this->wheres);

        $this->query($this->query);

        // return $this->query;
        // return $this->params;

        $this->execute($this->params);

        return $this->rowCount();
    }

    public function toSql()
    {
        $this->executeQuery();

        return $this->query;
    }

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
