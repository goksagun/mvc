<?php namespace App;

use App\Controller;

/**
* UserController
*/
class UserController extends Controller
{

    public function index()
    {
        $db = new Database('mysql:dbname=test;dbhost=localhost', 'root', 'secret');

        // Simple queries
        // $rows = $db->run("SELECT * FROM users");
        // basically the same as ...
        $rows = $db->query("SELECT * FROM users")->fetchAll(\PDO::FETCH_OBJ);

        echo count($rows) .' records<br>';
        foreach ($rows as $key=>$row) {
            echo $row->email .'<br>';
        }
        // close connection
        $db = null;
    }

    public function create()
    {
        echo 'user create method';
    }

    public function edit($id)
    {
        echo 'user edit method parametter: '.$id;
    }
}
