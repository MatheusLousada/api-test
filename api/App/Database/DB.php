<?php

namespace App\Database;

class DB
{
    private $conn;

    public function __construct()
    {
        $this->conn = new \mysqli("containers-us-west-20.railway.app", "root", "edbNCXlqmZ8NXQGqYgps", "7090");

        http_response_code(200);
        echo json_encode(["con" => $this->conn]);
        exit;

        if ($this->conn->connect_error) {
            http_response_code(500);
            echo json_encode(["error" => 'Connection failed']);
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
