<?php

namespace App\Core;

use App\Database\DB;

class Model
{
    private static $connection;

    public static function getConnection()
    {
        if (!isset(self::$connection)) {


            http_response_code(200);
            echo json_encode(["login" => 'aqui indentro']);
            exit;

            $db = new DB();
            self::$connection = $db->getConnection();
        }


        http_response_code(200);
        echo json_encode(["login" => 'aqui infora']);
        exit;

        return self::$connection;
    }

    public function setConnection(\mysqli $connection)
    {
        self::$connection = $connection;
    }
}
