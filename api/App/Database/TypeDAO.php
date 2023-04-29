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
        try {
            $stmt = $this->db->prepare('SELECT * FROM types WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result ? new Type($result['id'], $result['description']) : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM types');
            $stmt->execute();
            $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $types = array();
            foreach ($results as $result) {
                $type = new Type($result['id'], $result['description']);
                $types[] = [
                    'id' => $type->getId(),
                    'description' => $type->getDescription()
                ];
            }
            return $types;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
