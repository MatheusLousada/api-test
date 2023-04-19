<?php

namespace App\Database;

class DB
{
    private $conn;

    public function __construct()
    {
        echo json_encode(["error" => 'veio aqui']);
        $this->conn = new \mysqli("containers-us-west-20.railway.app", "root", "railway", "7090");
        if ($this->conn->connect_error) {
            // die("Connection failed: " . $this->conn->connect_error);
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
