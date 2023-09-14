<?php

require __DIR__ . "/vendor/autoload.php";

$router = new \Crellan\App\Core\RouterCore($_SERVER['HTTP_HOST']);


$router->get()->route('/', 'AppController@home', 'home');
$router->get()->route('/contact', 'AppController@contact', 'contact');
$router->get()->route('/blog/{vaga}', 'AppController@blog', 'blog');
$router->get()->route('/about', 'AppController@about', 'about');


$router->dispatch();