<?php

namespace App\Database;

use App\Models\ProductAttribute;

class ProductAttributeDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function save(ProductAttribute $productAtributte): mixed
    {
        try {
            $sku = $productAtributte->getProduct()->getSku();
            $productId = $productAtributte->getAttribute()->getId();
            $value = $productAtributte->getValue();

            $stmt = $this->db->prepare('INSERT INTO product_attributes (product_sku, attribute_id, value) VALUES (?, ?, ?)');
            $stmt->bind_param('sid', $sku,  $productId, $value);
            $stmt->execute();
            $productAtributte->setId($this->db->insert_id);
            return $productAtributte->getId();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete(ProductAttribute $productAtributte): mixed
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM product_attributes WHERE id = ?');
            $stmt->bind_param('i', $productAtributte->getId());
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
