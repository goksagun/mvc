<?php namespace App;

/**
* Message
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Message extends \Facade
{

    function __construct()
	{
		//
	}

    /**
     * @param string $key
     * @return bool
     */
    public function has($key='')
	{
		return Flash::has('messages.' . $key);
	}

    /**
     * @param string $key
     * @param null $default
     * @return null
     */
    public function get($key='', $default=null)
	{
		return isset($key) ? Flash::get('messages.' . $key, $default) : Flash::get('messages');
	}

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function set($key='', $value='')
	{
		return Flash::put('messages.' . $key, $value);
	}

    /**
     * @return null
     */
    public function all()
	{
		return Flash::get('messages');
	}
}