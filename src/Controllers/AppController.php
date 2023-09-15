<?php

namespace Crellan\App\Controllers;

class AppController
{


    public function home()
    {
        var_dump($_SERVER);
    }

    public function contact()
    {
        var_dump($_SERVER);
    }

    public function blog($array)
    {
        var_dump($array);
    }

    public function about()
    {
    }
}