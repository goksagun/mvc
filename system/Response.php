<?php namespace App;

/**
* Response
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Response extends \Facade
{

	function __construct()
	{
		# code...
	}

	public function redirect($uri='', $code=302)
	{
	    // // 301 Moved Permanently
	    // header("Location: /foo.php",TRUE,301);

	    // // 302 Found
	    // header("Location: /foo.php",TRUE,302);
	    // header("Location: /foo.php");

	    // // 303 See Other
	    // header("Location: /foo.php",TRUE,303);

	    // // 307 Temporary Redirect
	    // header("Location: /foo.php",TRUE,307);

	    header("Location : $uri", TRUE, $code);
	    exit();
	}

	function __destruct()
	{
		
	}
}