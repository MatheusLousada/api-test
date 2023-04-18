<?php

namespace App\Database;

use App\Models\Type;

class TypeDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM types WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new Type($result['id'], $result['description']) : null;
    }
}
