<?php

namespace Crellan\App\Controllers;

use Crellan\App\Models\UserModel;

class AppController
{


    public function home()
    {
        $user = new UserModel();
        $user->all();
        var_dump($user);
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