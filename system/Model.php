<?php namespace App;

/**
* Model
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Model extends QB
{
    protected $table;

    private static $qb;

    function __construct()
    {
        if (is_null($this->table)) {
            $this->table = underscore(pluralize(parse_classname(get_called_class())));
        }

//        self::$qb = $this->table;
        self::$qb = $this->db();
    }

    public function db()
    {
        return QB::table($this->table);
    }

    public function get()
    {
//        return QB::table(self::$qb);
//        return new QB(self::$qb);
        return self::$qb;
    }

    public function getTable()
    {
        return $this->table;
    }

}
