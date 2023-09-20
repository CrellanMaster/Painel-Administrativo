<?php

namespace Crellan\App\Traits;

use Crellan\App\Core\ConfigCore;
use PDO;
use PDOException;

trait Connection
{
    public function connect(): string|PDO
    {
        try {
            $conn = new PDO('mysql:host=' . ConfigCore::DB_HOST . ';dbname=' . ConfigCore::DB_NAME, ConfigCore::DB_USERNAME, ConfigCore::DB_PASSWORD);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        return $conn;
    }

    public function closeConnect($conn): void
    {
        $conn = null;
    }

}