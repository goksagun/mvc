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
        //
	}

    /**
     * @param string $key
     * @return bool
     */
    public function has($key='')
	{
		return Session::has('flash.' . $key);
	}

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function put($key='', $value='')
	{
		return Session::put('flash.' . $key, $value);
	}

    /**
     * @return null
     */
    public function all()
	{
		return Session::get('flash');
	}

    /**
     * @param string $key
     * @param bool $default
     * @return null
     */
    public function get($key='', $default=false)
	{
		return Session::get('flash.' . $key, $default);
	}

    /**
     * @param string $key
     * @return bool
     */
    public function forget($key='')
	{
		unset($_SESSION[$key]);
		return true;
	}

    /**
     *
     */
    public function destroy()
	{
		Session::forget('flash');
	}

    /**
     * @return bool
     */
    public function refresh()
    {
        return session_regenerate_id(true);
    }
}