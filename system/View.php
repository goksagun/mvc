<?php namespace App;

/**
* View
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class View
{

	private $view;

	protected $data = array();

    function __construct($view, $data)
    {
        $this->setViewFile($view);
        $this->setData($data);

        $this->setView();
    }

    public static function render($view = '', $data = array())
    {
    	return new View($view, $data);
    }

    public function setView()
    {
    	extract($this->data);

        ob_start();

        if (file_exists(views_path($this->view))) {
        	include views_path($this->view);
        } else {
        	throw new \Exception("View file '{$this->view}' not found");
        }
        
        $content = ob_get_contents();
        
        ob_end_clean();

        echo $content;
    }

    public function setViewFile($view = '')
    {
    	$this->view = $view . '.php';
    }

    public function setData($data = array())
    {
    	$this->data = $data;
    }

    public static function __callStatic($function, $arguments)
    {
        call_user_func_array($function, $arguments);
    }
}
