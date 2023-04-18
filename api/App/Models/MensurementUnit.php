<?php

namespace App\Models;

use App\Core\Model;
use App\Database\MensurementUnitDAO;

class MensurementUnit extends Model
{
    private mixed $id;
    private string $symbol;

    public function __construct(string $symbol, mixed $id)
    {
        $this->id = $id;
        $this->symbol = $symbol;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public static function getById(int $id): MensurementUnit
    {
        $dao = new MensurementUnitDAO(Model::getConnection());
        return $dao->getById($id);
    }
}
