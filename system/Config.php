<?php namespace App;

/**
* Config
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Config extends \Facade
{
    /**
     * @var
     */
    private $path;

    /**
     * @var array
     */
    private $config = array();

    /**
     * @param string $config
     */
    public function __construct($config = '')
    {
        $this->load();
    }

    /**
     * @param string $path
     * @return $this
     */
    public function load($path='')
    {
        $this->setPath();

        $path = ($path) ? $path : $this->getpath();

        foreach (glob("$path/*") as $filename) {

            $this->config[basename($filename, '.php')] = require $filename;
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function set($key='', $value='')
    {
        return array_set($this->config, $key, $value);
    }

    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    public function get($key='', $default=null)
    {
        return array_get($this->config, $key, $default);
    }

    /**
     * @param string $path
     */
    public function setPath($path='')
    {
        $this->path = base_path().'/config';
    }

    /**
     * @return mixed
     */
    public function getpath()
    {
        return $this->path;
    }
}
