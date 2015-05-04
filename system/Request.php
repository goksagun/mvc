<?php namespace App;

/**
* Request
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Request extends \Facade
{
    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $post;
    /**
     * @var
     */
    private $get;
    /**
     * @var
     */
    private $request;

    /**
     * @param $method
     * @param $post
     * @param $get
     * @param $request
     */
    function __construct($method, $post, $get, $request)
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->post = $_POST;
		$this->get = $_GET;
		$this->request = $_REQUEST;
	}

    /**
     * @return mixed
     */
    public function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

    /**
     * @param string $key
     * @return mixed
     */
    public function post($key='')
	{
		return isset($_POST[$key]) ? $_POST[$key] : $_POST;
	}

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key='')
	{
		return isset($_GET[$key]) ? $_GET[$key] : $_GET;
	}

    /**
     * @param string $key
     * @return mixed
     */
    public function all($key='')
	{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $_REQUEST;
	}

    /**
     * @param string $key
     * @return null
     */
    public function old($key='')
	{
		return isset($key) ? Flash::get('old.' . $key) : Flash::get('old');
	}

    /**
     *
     */
    function __destruct()
	{
		$_POST = array();
		$_GET = array();
		$_REQUEST = array();
	}
}