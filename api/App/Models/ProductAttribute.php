<?php

namespace App\Models;

use App\Core\Model;
use App\Database\ProductAttributeDAO;
use App\Models\Attribute;
use App\Models\ProductAbstract;

class ProductAttribute extends Model
{
    private mixed $id;
    private ProductAbstract $product;
    private Attribute $attribute;
    private float $value;

    public function __construct(ProductAbstract $product, Attribute $attribute, float $value, mixed $id = null)
    {
        $this->id = $id;
        $this->product = $product;
        $this->attribute = $attribute;
        $this->value = $value;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getProduct(): ProductAbstract
    {
        return $this->product;
    }

    public function setProduct(ProductAbstract $product): void
    {
        $this->product = $product;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(Attribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function save(): mixed
    {
        $dao = new ProductAttributeDAO($this->getConnection());
        return $dao->save($this);
    }

    public function delete(): mixed
    {
        $dao = new ProductAttributeDAO($this->getConnection());
        return $dao->delete($this);
    }

    public static function getById(int $id): ProductAttribute
    {
        $dao = new ProductAttributeDAO(Model::getConnection());
        return $dao->getById($id);
    }

    public function __toString()
    {
        return $this->attribute;
    }
}
