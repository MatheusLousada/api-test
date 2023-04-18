<?php

namespace App\Models;

use App\Core\Model;
use App\Database\UserAuthDAO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserAuth extends Model
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getToken(): mixed
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function login()
    {
        $dao = new UserAuthDAO($this->getConnection());
        $userAuth = $dao->getByEmail($this->email);
        if ($userAuth) {

            if ($userAuth->getPassword() != $this->password) {
                return [
                    'status' => 401,
                    'success' => false,
                    'msg' => 'Unauthorized. Wrong password.'
                ];
            }

            return [
                'status' => 200,
                'success' => true,
                'token' => $userAuth->getToken()
            ];
        }

        $key = 'hBmzGhfpv/By77yWM966lQ==';
        $payload = [
            "email" => $this->email,
            "password" => $this->password
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        $this->token = $jwt;
        $userAuthenticationCreated = $dao->create($this);
        if ($userAuthenticationCreated === true) {
            return [
                'status' => 201,
                'success' => true,
                'token' => $this->token
            ];
        }

        return [
            'status' => 500,
            'success' => false,
            'msg' => $userAuthenticationCreated
        ];
    }

    public static function authenticate(string $token): array
    {
        $key = 'hBmzGhfpv/By77yWM966lQ==';
        $token = preg_replace('/^Bearer\s/', '', $token);

        try {
            $decoded = JWT::decode($token,  new Key($key, 'HS256'));
            $dao = new UserAuthDAO(Model::getConnection());
            $userAuth = $dao->getByEmail($decoded->email);

            if ($userAuth->getPassword() != $decoded->password) {
                return [
                    'status' => 401,
                    'success' => false,
                    'msg' => 'Unauthorized. Wrong token.'
                ];
            }

            return [
                'status' => 200,
                'success' => true
            ];
        } catch (\Throwable $th) {
            return [
                'status' => 500,
                'success' => false,
                'msg' => $th->getMessage()
            ];
        }
    }
}
