<?php namespace App;

/**
* Router
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Router
{
    /**
     * Current url
     *
     * @var string
     */
    private $uri;

    /**
     * Current controller
     *
     * @var string
     */
    private $controller;

    /**
     * Current action to controller method
     *
     * @var string
     */
    private $action;

    /**
     * Current method parametters
     *
     * @var array
     */
    private $params;

    /**
     * contractor
     */
    function __construct()
    {
        $this->setUri();

        $parsedUrl = parse_url($this->getUri());

        $paths = explode('/', $parsedUrl['path']);

        $controller = strlen($paths[1]) ? $paths[1] : 'home';

        $action = (isset($paths[2]) && $paths[2] != '') ? $paths[2] : 'index';

        if (isset($paths[3])) {
            $params = array_slice($paths, 3);
        } else {
            $params = array();
        }

        // dd($params);

        $this->setController($controller);

        $this->setAction($action);

        $this->setParams($params);

        $this->checkFlash();

        // dd($this);
    }

    /**
     * Set route and map controller
     *
     * @access private
     */
    private function set()
    {
        // dd($this);
        $controllerFile = app_path().'/controllers/'.ucfirst($this->getController()).'.php';

        if (file_exists($controllerFile)) {

            $handler = array(__NAMESPACE__ .'\\'.$this->getController(), $this->getAction());
            $params = $this->getParams();

            if (is_callable($handler)) {
                return call_user_func_array($handler , $params);
                // return call_user_method_array($this->getAction(), __NAMESPACE__ .'\\'.$this->getController(), $this->getParams());
            } else {
                throw new \Exception("Page not found");
            }

            // Runs php 5.6+ TODO 5.6- new solution
            // return empty($params) ? $controller->$action() : $controller->$action(...$params);
        } else {
            throw new \Exception("Page not found");
        }
    }

    /**
     * Dispatch route
     *
     * @return mix
     */
    public function dispatch()
    {
        return $this->set();
    }

    public function checkFlash()
    {
        if (Session::has('flash_expires')) {
            Session::put('flash', []);
        }

        if (Session::get('flash')) {
            $expires = Session::put('flash_expires', true);
        } else {
            Session::forget('flash_expires');
        }
    }

    /**
     * Set uri
     *
     * @param string $uri
     */
    private function setUri($uri='')
    {
        $this->uri = $uri ? $uri : get_url();
    }

    /**
     * Get uri
     *
     * @return string
     */
    private function getUri()
    {
        return $this->uri;
    }

    /**
     * Set controller
     *
     * @param string $controller
     */
    private function setController($controller='')
    {
        $controller =   ucfirst($controller).'Controller';

        $this->controller = $controller;
    }

    /**
     * Get controller
     *
     * @return string
     */
    private function getController()
    {
        return $this->controller;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    private function setAction($action='')
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string
     */
    private function getAction()
    {
        return $this->action;
    }

    /**
     * Set params
     *
     * @param string $params
     */
    private function setParams($params=array())
    {
        $this->params = $params;
    }

    /**
     * Get params
     *
     * @return string
     */
    private function getParams()
    {
        return $this->params;
    }
}
