<?php

namespace App\Models;

use App\Core\Model;
use App\Database\ProductDAO;

abstract class ProductAbstract extends Model
{
    private mixed $id;
    private string $sku;
    private string $name;
    private float $price;
    private Type $type;
    protected array $productAttributes = [];

    public function __construct(string $sku, string $name, string $price, Type $type, mixed $id)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): void
    {
        $this->type = $type;
    }

    public function getAttributes(): array
    {
        $attributes[] = [
            'description' => $this->productAttributes[0]->getAttribute()->getDescription(),
            'value' => $this->productAttributes[0]->getValue(),
            'measurement_unit' => $this->productAttributes[0]->getAttribute()->getMensurementUnit()->getSymbol()
        ];

        return $attributes;
    }

    public function setAttributes(array $productAttributes): void
    {
        $this->productAttributes = $productAttributes;
    }

    public static function getById(int $id): Product
    {
        $dao = new ProductDAO(Model::getConnection());
        return $dao->getById($id);
    }

    public static function getBySku(string $sku): Product
    {
        $dao = new ProductDAO(Model::getConnection());
        return $dao->getBySku($sku);
    }

    public static function getAll(): array
    {
        $dao = new ProductDAO(Model::getConnection());
        return $dao->getAll();
    }

    public function save(): mixed
    {
        $dao = new ProductDAO($this->getConnection());
        return $dao->save($this);
    }

    public function delete(): mixed
    {
        $dao = new ProductDAO($this->getConnection());
        return $dao->delete($this);
    }
}
