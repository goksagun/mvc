<?php namespace App;

use App\Controller;

/**
* HomeController
*/
class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }
}
