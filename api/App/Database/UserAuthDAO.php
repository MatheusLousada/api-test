<?php

namespace App\Database;

use App\Models\UserAuth;

class UserAuthDAO
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function getByEmail(string $email): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users_authetications WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result ? new UserAuth($result['email'], $result['password'], $result['token']) : null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(UserAuth $authentication): mixed
    {
        try {
            $email = $authentication->getEmail();
            $password = $authentication->getPassword();
            $token = $authentication->getToken();

            $stmt = $this->db->prepare('INSERT INTO users_authetications (email, password, token) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $email, $password, $token);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }
}
