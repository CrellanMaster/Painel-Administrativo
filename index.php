<?php

require __DIR__ . "/vendor/autoload.php";

$router = new \Crellan\App\Core\RouterCore($_SERVER['HTTP_HOST']);


$router->get()->route('/', 'AppController@home', 'home');
$router->get()->route('/contact', 'AppController@contact', 'contact');
$router->get()->route('/blog', 'AppController@contact', 'blog');
$router->get()->route('/blog/{slug}/', 'AppController@blog', 'blog.slug');
$router->get()->route('/about', 'AppController@about', 'about');


$router->dispatch();