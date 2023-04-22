<?php

namespace App\Models;

use App\Models\ProductAbstract;

class ProductFurniture extends ProductAbstract
{
    public function __construct(string $sku, string $name, string $price, Type $type, mixed $id = null)
    {
        parent::__construct($sku, $name, $price, $type, $id);
    }
}
