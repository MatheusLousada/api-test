<?php

namespace App\Models;

use App\Models\ProductAbstract;

class ProductFurniture extends ProductAbstract
{
    public function __construct(string $sku, string $name, string $price, Type $type, mixed $id = null)
    {
        parent::__construct($sku, $name, $price, $type, $id);
    }

    public function getAttributes(): array
    {
        $valuesArray = [];
        foreach ($this->productAttributes as $productAttribute)
            $valuesArray[] = $productAttribute->getValue();

        $values = implode("X", $valuesArray);
        $attributes[] = [
            'description' => "Dimensions",
            'value' => $values,
            'measurement_unit' => $this->productAttributes[0]->getAttribute()->getMensurementUnit()->getSymbol()
        ];

        return $attributes;
    }
}
