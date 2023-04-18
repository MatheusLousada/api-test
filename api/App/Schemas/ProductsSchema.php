<?php
class ProductsSchema
{
    private array $body;
    private array $requiredFields = ['sku', 'name', 'price', 'type_id'];
    private array $optionalFields = ['attributes'];

    public function __construct($body)
    {
        $this->body = $body;
    }

    private function verifyRequiredFields()
    {
        foreach ($this->requiredFields as $field) {
            if (!isset($this->body['product'][$field]))
                throw new Exception("$field is a required field");
        }
    }

    private function verifyInvalidFields()
    {
        foreach ($this->body['product'] as $key => $value) {
            if (!in_array($key, $this->requiredFields) && !in_array($key, $this->optionalFields))
                throw new Exception("$key is not a valid field");
        }
    }

    private function verifyAttributes()
    {
        if (isset($this->body['product']['attributes'])) {
            foreach ($this->body['product']['attributes'] as $attribute) {
                if (!isset($attribute['id']) || !isset($attribute['value']))
                    throw new Exception("Invalid attributes structure");
            }
        }
    }

    public function validate()
    {
        try {
            $this->verifyRequiredFields();
            $this->verifyInvalidFields();
            $this->verifyAttributes();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
