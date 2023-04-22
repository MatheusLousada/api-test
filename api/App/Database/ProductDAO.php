<?php

namespace App\Database;

use App\Models\ProductAbstract;
use App\Models\Product;
use App\Models\Type;
use App\Models\ProductAttribute;

class ProductDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getById(int $id): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $type = Type::getById($result['type_id']);
            return $result ? new Product($result['sku'], $result['name'], $result['price'], $type, $result['id']) : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getBySku(string $sku): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM products WHERE sku = ?');
            $stmt->bind_param('s', $sku);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $type = Type::getById($result['type_id']);
            return $result ? new Product($result['sku'], $result['name'], $result['price'], $type, $result['id']) : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAll(): array
    {
        try {

            $products = array();
            $stmt = $this->db->prepare(
                'SELECT 
                    products.id,
                    products.sku,
                    products.name,
                    products.price,
                    products.type_id,
                    GROUP_CONCAT(product_attributes.id) AS product_attributes_id
                FROM products
                LEFT JOIN product_attributes ON 
                    product_attributes.product_sku = products.sku
                GROUP BY 
                    products.id, products.sku, products.name, products.price, products.type_id
            '
            );

            $stmt->execute();
            $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            foreach ($results as $result) {
                $type = Type::getById($result['type_id']);
                $dinamycProduct = 'Product' . ucfirst(strtolower($type->getDescription()));
                if (file_exists("App/Models/" .  $dinamycProduct . ".php")) {
                    require_once "App/Models/" . $dinamycProduct . ".php";
                    $dinamycProduct = "App\Models\\" . $dinamycProduct;

                    $attributes = array();
                    $productAttribute_id = $result['product_attributes_id'];
                    $product_attributes_ids = [];

                    http_response_code(200);

                    if (strpos($productAttribute_id, ',') !== false) {
                        echo json_encode(["msg" => "aqui1"]);
                        $product_attributes_ids = explode(',', $productAttribute_id);
                    } else {
                        echo json_encode(["msg" => "aqui2"]);
                        $product_attributes_ids[0] = $productAttribute_id;
                    }

                    echo json_encode(["productAttribute_id" => $productAttribute_id]);
                    echo json_encode(["msg" => "aqui2"]);
                    exit;

                    if (!empty($product_attributes_ids[0])) {
                        foreach ($product_attributes_ids as $product_attributes_id) {

                            $productAttribute = ProductAttribute::getById($product_attributes_id);

                            $attributes[] = [
                                'description' => $productAttribute->getAttribute()->getDescription(),
                                'value' => $productAttribute->getValue(),
                                'measurement_unit' => $productAttribute->getAttribute()->getMensurementUnit()->getSymbol()
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
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function save(ProductAbstract $product): mixed
    {
        try {

            $sku = $product->getSku();
            $name = $product->getName();
            $price = $product->getPrice();
            $typeId = $product->getType()->getId();

            $stmt = $this->db->prepare('INSERT INTO products (sku, name, price, type_id) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssdi', $sku, $name, $price, $typeId);
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
        try {
            $productId = $product->getId();
            $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
            $stmt->bind_param('i', $productId);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }
}
