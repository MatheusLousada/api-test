<?php

namespace App\Core;

use App\Controllers\AuthController;
use App\Requests\HttpRequest;

class Router
{
    private array $request = [];
    private array $route = [];
    private string $context;

    public function __construct()
    {
        $httpRequest = new HttpRequest();
        $this->request = $httpRequest->getRequest();
        $context = explode('/', $this->request['uri']);
        $context = is_numeric(end($context)) ? end(array_slice($context, -2, 1)) : end($context);
        $this->context = $context;
        $this->route = [
            'path' => '/' . $this->context,
            'controller' => ucfirst($this->context) . 'Controller'
        ];
    }

    public function handleRequest()
    {
        switch ($this->request['method']) {
            case 'GET':
                return $this->get();
                break;
            case 'POST':
                $this->validateBody();
                return $this->post();
                break;
            case 'DELETE':
                return $this->delete();
                break;
            default:
                echo json_encode(["error" => "Invalid method"]);
                break;
        }
    }

    private function get(): void
    {
        $this->route['verb'] = 'GET';
        $this->route['method'] = 'index';
    }

    private function post(): void
    {
        $this->route['verb'] = 'POST';
        $this->route['method'] = 'create';
    }

    private function delete(): void
    {
        $this->route['verb'] = 'DELETE';
        $this->route['method'] = 'delete';
        $context = explode('/', $this->request['uri']);
        // $this->request['id'] = end($context);
    }

    private function auth(): mixed
    {
        if ($this->context == 'login') {

            list("email" => $email, "password" => $password) = $this->request["body"];
            $auth = new AuthController($email, $password);
            $responseAuth = $auth->login();

            http_response_code($responseAuth['status']);

            if ($responseAuth['success'] == true) {
                echo json_encode(["token" => $responseAuth['token']]);
                exit;
            }

            echo json_encode(["error" => $responseAuth['msg']]);
            exit;
        }

        $headers = getallheaders();
        if ($headers["Authorization"]) {
            $authenticated = AuthController::authenticate($headers["Authorization"]);
            return $authenticated;
        }

        return false;
    }

    private function validateBody(): void
    {
        if (empty($this->request['body']) || $this->request['body'] == NULL) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request body"]);
            exit;
        }

        $schemaFileName = ucfirst($this->context) . 'Schema';
        if (file_exists("App/Schemas/" .  $schemaFileName . ".php")) {
            require_once "App/Schemas/" . $schemaFileName . ".php";
            $schema = new  $schemaFileName($this->request['body']);
            $validated = $schema->validate();
            if ($validated !== true) {
                echo json_encode(["response" => $validated]);
                exit;
            }
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Resource not found"]);
            exit;
        }
    }

    public function run(): void
    {
        $responseAuth = $this->auth();
        if (!$responseAuth['success']) {
            http_response_code($responseAuth['status']);
            echo json_encode(["error" => 'Unauthorized. ' . $responseAuth['msg']]);
            exit;
        }

        $this->handleRequest();

        if (file_exists("App/Controllers/" .  $this->route['controller'] . ".php")) {
            require_once "App/Controllers/" . $this->route['controller'] . ".php";
            $controller = new $this->route['controller']();
            call_user_func_array([$controller, $this->route['method']], [$this->request]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Resource not found"]);
            exit;
        }
    }
}
