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
        $port = 7090;
        $protocol = "TCP";

        $conn = mysqli_connect($servername, $username, $password, "", $port, $protocol);
        if (!$conn) {
            die("Falha na conexão: " . mysqli_connect_error());
        }

        echo "Conexão bem sucedida";

        // if ($this->conn->connect_errno) {
        //     http_response_code(500);
        //     echo json_encode(["error" => 'Connection failed: ' + $this->conn->connect_error]);
        //     exit;
        // }
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
