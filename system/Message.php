<?php namespace App;

/**
* Message
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Message extends \Facade
{
	function __construct($method, $post, $get, $request)
	{
		//
	}

	public function has($key='')
	{
		return Flash::has('messages.' . $key);
	}

	public function get($key='', $default=null)
	{
		return isset($key) ? Flash::get('messages.' . $key, $default) : Flash::get('messages');
	}

	public function set($key='', $value='')
	{
		return Flash::put('messages.' . $key, $value);
	}

	public function all()
	{
		return Flash::get('messages');
	}
}