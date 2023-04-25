<?php

use App\Models\Product;
use App\Models\ProductAbstract;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\Type;
use App\Database\DB;

class ProductsController
{
    private Product $product;
    private string $dinamycProduct;
    private DB $db;
    private $con;
    private $request;
    private $savedProduct;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        echo json_encode(Product::getAll(), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function create($request)
    {
        $this->request = $request;
        list("sku" => $sku, "name" => $name, "price" => $price, "type_id" => $typeId) = $this->request["body"]["product"];
        $type = Type::getById($typeId);

        $this->product = new Product($sku, $name, $price, $type);
        $this->dinamycProduct = 'Product' . ucfirst(strtolower($this->product->getType()->getDescription()));

        if (file_exists("App/Models/" .  $this->dinamycProduct . ".php")) {

            $this->db->setAutoCommit(false);
            $this->initConnection();

            $this->savedProduct = $this->saveProduct();
            if ($this->savedProduct instanceof ProductAbstract) {
                $this->vinculateAttributes();
                $this->savedProduct = $this->savedProduct->getId();
            }

            $this->closeConnection();

            echo json_encode(['response' => $this->savedProduct], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Product type not implemented"]);
            exit;
        }
    }

    private function initConnection()
    {
        $this->con = $this->db->getConnection();
        $this->con->begin_transaction();
    }

    private function closeConnection()
    {
        $this->con->commit();
        $this->con->close();
    }

    public function delete($request)
    {

        $this->request = $request;
        $body = $this->request["body"];

        // $product = Product::getById($request['id']);
        // $deleted = $product->delete();
        echo json_encode(['response' => gettype($body)], JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function saveProduct()
    {
        require_once "App/Models/" . $this->dinamycProduct . ".php";
        $dinamycProductModel = "App\Models\\" . $this->dinamycProduct;
        $product = new $dinamycProductModel($this->product->getSku(), $this->product->getName(), $this->product->getPrice(), $this->product->getType());
        $product->setConnection($this->con);
        return $product->save();
    }

    private function vinculateAttributes()
    {
        $notLinkedsAttributes = array();
        foreach ($this->request["body"]["product"]["attributes"] as $att) {
            $attribute = Attribute::getById($att['id']);
            $productAttribute = new ProductAttribute($this->savedProduct, $attribute, $att['value']);
            $productAttribute->setConnection($this->con);
            $linked = $productAttribute->save();
            if (!is_int($linked))
                $notLinkedsAttributes[] = $attribute->getDescription();
        }

        if (!empty($notLinkedsAttributes)) {
            $this->con->rollback();
            $this->con->close();
            $errorMessage = "Sorry, we couldn't save the product due to an error while saving the attributes: " . implode(',', $notLinkedsAttributes) . ". Please try again later or contact our support team for assistance.";
            echo json_encode(['error' => $errorMessage], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
