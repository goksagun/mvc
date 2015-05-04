<?php 

/**
* Facade
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Facade
{

    /**
     * @param $function
     * @param $arguments
     */
    public static function __callStatic($function, $arguments)
    {
        call_user_func_array($function, $arguments);
    }
}