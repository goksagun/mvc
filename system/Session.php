<?php namespace App;

/**
* Session
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Session extends \Facade
{

	function __construct()
	{
		// ini_set('session.save_handler', 'files');
		// session_set_save_handler($this, true);
		// session_save_path(storage_path() . '/sessions');

		// ini_set('session.use_cookies', 1);
  //       ini_set('session.use_only_cookies', 1);

  //       session_name('mvc_session');

		// session_start();		if ( is_session_started() === FALSE ) session_start();
	}

	public function has($key='')
	{
		return array_has($_SESSION, $key);
	}

	public function put($key, $value)
	{
		return array_set($_SESSION, $key, $value);
	}

	public function all()
	{
		return $_SESSION;
	}

	public function get($key, $default=null)
	{
		return array_has($_SESSION, $key) ? array_get($_SESSION, $key, $default) : $default;
	}

	public function forget($key='')
	{
		unset($_SESSION[$key]);
		return true;
	}

	public function destroy()
	{
		// if (session_id() === '') {
  //           return false;
  //       }

        $_SESSION = [];

		// return session_destroy();
		return true;
	}

	public function refresh()
    {
        return session_regenerate_id(true);
    }

	function __destruct()
	{
		$_SESSION = [];	
	}
}