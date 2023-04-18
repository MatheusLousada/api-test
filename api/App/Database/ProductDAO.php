<?php

namespace App\Database;

use App\Models\ProductAbstract;
use App\Models\Product;
use App\Models\Type;
use App\Models\Attribute;

class ProductDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $type = Type::getById($result['type_id']);
        return $result ? new Product($result['sku'], $result['name'], $result['price'], $type, $result['id']) : null;
    }

    public function getAll(): array
    {
        $products = array();
        $stmt = $this->db->prepare(
            'SELECT 
                products.id,
                products.sku,
                products.name,
                products.price,
                products.type_id,
                GROUP_CONCAT(product_attributes.attribute_id) AS attributes_id
            FROM products
            LEFT JOIN product_attributes ON
                product_attributes.product_sku = products.sku
            GROUP BY products.sku'
        );

        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($results as $result) {
            $type = Type::getById($result['type_id']);
            $dinamycProduct = 'Product' . ucfirst(strtolower($type->getDescription()));
            if (file_exists("../App/Models/" .  $dinamycProduct . ".php")) {
                require_once "../App/Models/" . $dinamycProduct . ".php";
                $dinamycProduct = "App\Models\\" . $dinamycProduct;

                $attributes = array();
                $attributes_ids = explode(',', $result['attributes_id']);
                if (!empty($attributes_ids[0])) {
                    foreach ($attributes_ids as $attribute_id) {
                        $attribute = Attribute::getById($attribute_id);
                        $attributes[] = [
                            'description' => $attribute->getDescription(),
                            'measurement_unit' => $attribute->getMensurementUnit()->getSymbol()
                        ];
                    }
                }

                $product = new $dinamycProduct($result['sku'], $result['name'], $result['price'], $type, $result['id']);
                $products[] = [
                    'id' => $product->getId(),
                    'sku' => $product->getSku(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'type' => $product->getType()->getDescription(),
                    'attributes' => $attributes
                ];
            } else {
                http_response_code(404);
                return "Product type " . $type . " not implemented";
            }
        }

        return $products;
    }

    public function save(ProductAbstract $product): mixed
    {
        $stmt = $this->db->prepare('INSERT INTO products (sku, name, price, type_id) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssdi', $product->getSku(), $product->getName(), $product->getPrice(), $product->getType()->getId());
        try {
            $stmt->execute();
            $product->setId($this->db->insert_id);
            return $product;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }

    public function delete(ProductAbstract $product): mixed
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->bind_param('i', $product->getId());
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }
}
