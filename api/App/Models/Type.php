<?php

namespace App\Models;

use App\Core\Model;
use App\Database\TypeDAO;

class Type extends Model
{
    private int $id;
    private string $description;

    public function __construct(int $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
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

    public static function getById(int $id): Type
    {
        $dao = new TypeDAO(Model::getConnection());
        return $dao->getById($id);
    }
}
