<?php namespace App;

/**
* Model
*/
class Model
{
    protected $table;

    function __construct()
    {
        if (is_null($this->table)) {
            $this->table = underscore(pluralize(parse_classname(get_called_class())));
        }
    }

    public function db()
    {
        return DB::table($this->table);
    }

    // DB::table('users')->get()  =>  User::get()
    //
    public static function get()
    {
        return $this->db;
    }

}
