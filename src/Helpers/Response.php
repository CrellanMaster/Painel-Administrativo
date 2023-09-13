<?php

namespace Crellan\App\Helpers;

class Response
{

    public static function redirectIfError($message)
    {
        echo "<h1>$message</h1>";
    }
}