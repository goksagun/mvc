<?php namespace App;

use App\Session;
/**
* Flash
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Flash extends \Facade
{
	function __construct()
	{

	}

	public function has($key='')
	{
		return Session::has('flash.' . $key);
	}

	public function put($key='', $value='')
	{
		return Session::put('flash.' . $key, $value);
	}

	public function all()
	{
		return Session::get('flash');
	}

	public function get($key='', $default=false)
	{
		return Session::get('flash.' . $key, $default);
	}

	public function forget($key='')
	{
		// $_SESSION = [];
		// dd(array_set($_SESSION, $key, false));
		unset($_SESSION[$key]);
		return true;
	}

	public function destroy()
	{
		Session::forget('flash');
	}

	public function refresh()
    {
        return session_regenerate_id(true);
    }
}