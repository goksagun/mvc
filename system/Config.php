<?php namespace App;

/**
* Config
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Config extends \Facade
{
    private $path;

    private $config = array();

    public function __construct($config = '')
    {
        $this->load();
    }

    public function load($path='')
    {
        $this->setPath();

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
}
