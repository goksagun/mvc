<?php namespace App;

/**
* Connection
*
* @author  Burak Bolat
* @copyright burakbolat.com
*/
class Connection
{
    protected $link;
    private $dsn, $username, $password, $options;
    
    public function __construct($dsn, $username, $password, $options)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->connect();
    }
    
    public function connect()
    {
        $this->link = new \PDO($this->dsn, $this->username, $this->password, $this->options);
    }
    
    public function __sleep()
    {
        return array('dsn', 'username', 'password');
    }
    
    public function __wakeup()
    {
        $this->connect();
    }
}