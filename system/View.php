<?php namespace App;

/**
* View
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class View extends \Facade
{

    /**
     * @var
     */
    private $view;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param $view
     * @param $data
     * @throws \Exception
     */
    function __construct($view, $data)
    {
        $this->setViewFile($view);
        $this->setData($data);

        $this->setView();
    }

    /**
     * @param string $view
     * @param array $data
     * @return View
     */
    public static function render($view = '', $data = array())
    {
    	return new View($view, $data);
    }

    /**
     * @throws \Exception
     */
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

    /**
     * @param string $view
     */
    public function setViewFile($view = '')
    {
    	$this->view = $view . '.php';
    }

    /**
     * @param array $data
     */
    public function setData($data = array())
    {
    	$this->data = $data;
    }
}
