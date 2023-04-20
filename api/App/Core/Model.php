<?php

namespace App\Core;

use App\Database\DB;

class Model
{
    private static $connection;

    public static function getConnection()
    {
        if (!isset(self::$connection)) {

            $db = new DB();
            self::$connection = $db->getConnection();
        }

        return self::$connection;
    }

    public function setConnection(\mysqli $connection)
    {
        self::$connection = $connection;
    }
}
