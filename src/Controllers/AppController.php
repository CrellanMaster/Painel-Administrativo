<?php

namespace Crellan\App\Controllers;

use Crellan\App\Models\UserModel;

class AppController
{


    public function home()
    {
        $user = new UserModel();
        $users = $user->all(array('username', 'email'))
            ->where('id', '>', 0)
            ->get();
        var_dump($users);
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