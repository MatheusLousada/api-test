<?php

use App\Models\Type;
use App\Database\DB;

class TypesController
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        echo json_encode(Type::getAllWithAttributes(), JSON_UNESCAPED_UNICODE);
        exit;
    }
}
