<?php

require __DIR__ . "/vendor/autoload.php";

$router = new \Crellan\App\Core\RouterCore($_SERVER['HTTP_HOST']);


$router->route('/about', 'AppController@about', 'about');
$router->route('/about', 'AppController@about', 'about');

$router->dispatch();