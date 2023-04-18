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
        $stmt = $this->db->prepare('INSERT INTO product_attributes (product_sku, attribute_id, value) VALUES (?, ?, ?)');
        $stmt->bind_param('sid', $productAtributte->getProduct()->getSku(),  $productAtributte->getAttribute()->getId(), $productAtributte->getValue());
        try {
            $stmt->execute();
            $productAtributte->setId($this->db->insert_id);
            return $productAtributte->getId();
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function delete(ProductAttribute $productAtributte): mixed
    {
        $stmt = $this->db->prepare('DELETE FROM product_attributes WHERE id = ?');
        $stmt->bind_param('i', $productAtributte->getId());
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }
}
