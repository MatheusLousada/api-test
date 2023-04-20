<?php

namespace App\Database;

use App\Models\Attribute;
use App\Models\MensurementUnit;

class AttributeDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM attributes WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $measurement_unit = MensurementUnit::getById($result['measurement_unit_id']);
            return $result ? new Attribute($result['description'], $measurement_unit, $result['id']) : null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM attributes');
            $stmt->execute();
            $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $attributes = array();
            foreach ($results as $result) {
                $measurement_unit = MensurementUnit::getById($result['measurement_unit_id']);
                $attribute = new Attribute($result['description'], $measurement_unit, $result['id']);
                $attributes[] = [
                    'id' => $attribute->getId(),
                    'description' => $attribute->getDescription(),
                    'measurement_unit' => $attribute->getMensurementUnit()->getSymbol(),
                ];
            }
            return $attributes;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
