<?php

namespace App\Requests;

class HttpRequest
{
    private $method;
    private $uri;
    private $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->body = json_decode(file_get_contents('php://input'), true);
    }

    public function getRequest()
    {
        return [
            'method' => $this->method,
            'uri' => $this->uri,
            'body' => $this->body
        ];
    }
}
