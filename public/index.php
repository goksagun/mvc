<?php

require '../bootstrap/bootstrap.php';

Dotenv::load(base_path());

$router = new App\Router;

$router->dispatch();
