<?php

namespace Crellan\App\Helpers;

use Crellan\App\Core\ConfigCore;

class Response
{
    public static function responseException($e)
    {
        if (ConfigCore::ENVIRONMENT == 'DEBUG') {
            throw $e;
        } else {
            header('Location:' . $_SERVER['HTTP_HOST'], false, 500);
        }
    }

    public static function responseNotFound($e)
    {
        if (ConfigCore::ENVIRONMENT == 'DEBUG') {
            throw $e;
        } else {
            header('Location: /404', '', 404);
        }
    }
}