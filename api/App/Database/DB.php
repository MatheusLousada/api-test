<?php

namespace App\Database;

class DB
{
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new \mysqli("containers-us-west-12.railway.app", "root", "Z0YFpjMHB4cmAXH5dj6c", "7535");
        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode(["error" => $th]);
            exit;
        }

        if ($this->conn->connect_errno) {
            http_response_code(500);
            echo json_encode(["error" => 'Connection failed: ' + $this->conn->connect_error]);
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
