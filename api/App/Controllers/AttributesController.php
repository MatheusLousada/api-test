<?php

use App\Models\Attribute;
use App\Database\DB;

class AttributesController
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        echo json_encode(Attribute::getAll(), JSON_UNESCAPED_UNICODE);
        exit;
    }
}
