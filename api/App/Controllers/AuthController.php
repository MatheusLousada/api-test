<?php

namespace App\Controllers;

use App\Models\UserAuth;

class AuthController
{
    private string $email;
    private string $password;
    private mixed $token;

    public function __construct(string $email, string $password, mixed $token = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
    }

    public function login(): array
    {
        http_response_code(200);
        echo json_encode(["login" => 'veio aqui auth contoller']);
        exit;
        $userAuth = new UserAuth($this->email, $this->password);
        return $userAuth->login();
    }

    public static function authenticate(string $token): array
    {
        return UserAuth::authenticate($token);
    }
}
