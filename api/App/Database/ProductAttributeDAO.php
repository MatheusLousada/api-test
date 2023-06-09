<?php

namespace App\Database;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;

class ProductAttributeDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM product_attributes WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $product = Product::getBySku($result['product_sku']);
            $attribute = Attribute::getById($result['attribute_id']);
            return $result ? new ProductAttribute($product, $attribute, $result['value'], $result['id']) : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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
