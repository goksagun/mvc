<?php namespace App;

/**
* Model
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Model extends QB
{
    /**
     * @var
     */
    protected $table;

    /**
     *
     */
    function __construct()
    {
        if (is_null($this->table)) {
            $this->table = underscore(pluralize(parse_classname(get_called_class())));
        }
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function get($columns = array())
    {
        $model = new static;
        return QB::table($model->table)->get($columns);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array())
    {
        $model = new static;
        return QB::table($model->table)->all($columns);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function first($columns = array())
    {
        $model = new static;
        return QB::table($model->table)->first($columns);
    }

    /**
     * @param int $id
     * @param string $key
     * @return mixed
     */
    public function find($id = 0, $key = 'id')
    {
        $model = new static;
        return QB::table($model->table)->find($id, $key);
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function select($columns = array('*'))
    {
        $model = new static;
        return QB::table($model->table)->select($columns);
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
        $model = new static;
        return QB::table($model->table)->where($column, $operator, $value, $comperator);
    }

    /**
     * @param $key
     * @param string $operator
     * @param $value
     * @return $this
     */
    public function orWhere($key, $operator = '=', $value)
    {
        $model = new static;
        return QB::table($model->table)->orWhere($key, $operator, $value);
    }

    /**
     * @param string $key
     * @param string $order
     * @return $this
     */
    public function orderBy($key = '', $order = 'ASC')
    {
        $model = new static;
        return QB::table($model->table)->orderBy($key, $order);
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function take($limit = 1000)
    {
        $model = new static;
        return QB::table($model->table)->take($limit);
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit($limit = 1000)
    {
        $model = new static;
        return QB::table($model->table)->take($limit);
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function skip($offset = 0)
    {
        $model = new static;
        return QB::table($model->table)->skip($offset);
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset($offset = 0)
    {
        $model = new static;
        return QB::table($model->table)->offset($offset);
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public function insert(array $data = array())
    {
        $model = new static;
        return QB::table($model->table)->insert($data);
    }

    /**
     * @param array $data
     * @return int|mixed
     */
    public function create(array $data = array())
    {
        $model = new static;
        return QB::table($model->table)->insert($data);
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
        $model = new static;
        return QB::table($model->table)->update($data, $id, $key, $operator);
    }

    /**
     * @param int $id
     * @param string $key
     * @param string $operator
     * @return mixed
     */
    public function delete($id = 0, $key = 'id', $operator = '=')
    {
        $model = new static;
        return QB::table($model->table)->delete($id, $key, $operator);
    }

}
