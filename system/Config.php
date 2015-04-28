<?php namespace App;

/**
* Config
*/
class Config
{
    private $path;

    private $config = array();

    public function __construct()
    {
        $this->setPath();

        $this->load();
    }

    public function load($path='')
    {
        $path = ($path) ? $path : $this->getpath();

        foreach (glob("$path/*") as $filename) {

            $this->config[basename($filename, ".php")] = require $filename;
        }

        return $this;
    }

    public function set($key='', $value='')
    {
        return array_set($this->config, $key, $value);
    }

    public function get($key='', $default=null)
    {
        return array_get($this->config, $key, $default);
    }

    public function setPath($path='')
    {
        $this->path = base_path().'/config';
    }

    public function getpath()
    {
        return $this->path;
    }

    public static function __callStatic($function, $arguments)
    {
        call_user_func_array($function, $arguments);
    }
}
