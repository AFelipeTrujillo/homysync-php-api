<?php 

namespace App\Adapters\Http;

use App\Services\AuthService;
use Exception;

class AuthController {
    public function __construct(private AuthService $authService) {}

    public function register(?array $input) :array {

        if (!$input || !isset($input['email'], $input['password'], $input['name'])) {
           return [
               "status" => 400,
               "error" => "Invalid input. 'email', 'password', and 'name' are required."
           ];
        }

        try {
            $user = $this->authService->register($input['email'], $input['password'], $input['name']);
            return [
                "status" => 201,
                "data" => [
                    "id" => $user->id,
                    "email" => $user->email,
                    "name" => $user->name
                ]
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error" => $e->getMessage()
            ];
        }
    }
}

