<?php

namespace App\Adapters\Http;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware
{
    public static function validateToken(): array
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no proporcionado']);
            exit;
        }

        try {

            $jwt = $matches[1];
            $key = $_ENV["JWT_SECRET"] ?? null;
            $decode = JWT::decode($jwt, new Key($key, 'HS256'));
            return (array) $decode;

        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token inválido: ' . $e->getMessage()]);
            exit;
        }
    }
}