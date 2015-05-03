<?php namespace App;

/**
* Request
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Request extends \Facade
{
	private $method;
	
	private $post;
	private $get;
	private $request;

	function __construct($method, $post, $get, $request)
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->post = $_POST;
		$this->get = $_GET;
		$this->request = $_REQUEST;
	}

	public function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function post($key='')
	{
		return isset($_POST[$key]) ? $_POST[$key] : $_POST;
	}

	public function get($key='')
	{
		return isset($_GET[$key]) ? $_GET[$key] : $_GET;
	}

	public function all($key='')
	{
		return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $_REQUEST;
	}

	public function old($key='')
	{
		return isset($key) ? Flash::get('old.' . $key) : Flash::get('old');
	}

	function __destruct()
	{
		$_POST = array();
		$_GET = array();
		$_REQUEST = array();
	}
}