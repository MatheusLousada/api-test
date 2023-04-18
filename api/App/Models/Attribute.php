<?php

namespace App\Models;

use App\Core\Model;
use App\Database\AttributeDAO;

class Attribute extends Model
{
    private mixed $id;
    private string $description;
    private MensurementUnit $measurement_unit;

    public function __construct(string $description, MensurementUnit $measurement_unit, mixed $id)
    {
        $this->id = $id;
        $this->description = $description;
        $this->measurement_unit = $measurement_unit;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getMensurementUnit(): MensurementUnit
    {
        return $this->measurement_unit;
    }

    public function setMensurementUnit(MensurementUnit $measurement_unit): void
    {
        $this->measurement_unit = $measurement_unit;
    }

    public static function getById(int $id): Attribute
    {
        $dao = new AttributeDAO(Model::getConnection());
        return $dao->getById($id);
    }

    public static function getAll(): array
    {
        $dao = new AttributeDAO(Model::getConnection());
        return $dao->getAll();
    }
}
