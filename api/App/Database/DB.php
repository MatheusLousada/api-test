<?php

namespace App\Database;

class DB
{
    private $conn;

    public function __construct()
    {
        $this->conn = new \mysqli("localhost", "epiz_34025363", "eJEUeD6tNpK", "epiz_34025363_test");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
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
