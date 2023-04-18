<?php

namespace App\Database;

use App\Models\MensurementUnit;

class MensurementUnitDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM measurement_units WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new MensurementUnit($result['symbol'], $result['id']) : null;
    }
}
