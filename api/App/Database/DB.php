<?php

namespace App\Database;

class DB
{
    private $conn;

    public function __construct()
    {
        $servername = "containers-us-west-20.railway.app";
        $username = "root";
        $password = "edbNCXlqmZ8NXQGqYgps";
        $database = "railway";
        $port = 7090;
        $protocol = "TCP";

        $this->conn = mysqli_connect($servername, $username, $password, $database, $port, $protocol);
        if (!$this->conn) {
            http_response_code(500);
            echo json_encode(["error" => 'Connection failed: ' . mysqli_connect_error()]);
            exit;
        }
    }

    public function setAutoCommit($value)
    {
        $this->conn->autocommit($value);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function close()
    {
        $this->conn->close();
    }
}
