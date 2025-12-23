<?php 

namespace App\Adapters\Http;

use App\Services\AuthService;
use Exception;

class AuthController {
    public function __construct(private AuthService $authService) {}

    /**
     * Handle user registration
     * @param array|null $input
     * @return array
     */
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
    /**
     * Handle user login
     * @param array|null $input
     * @return array
     */
    public function login(?array $input) :array
    {
        if (!$input || !isset($input['email'], $input['password'])) {
            return [
                "status" => 400,
                "data" => [
                    "error" => "Invalid input. 'email' and 'password' are required."
                ]
            ];
        }

        try {
            $userData = $this->authService->login($input['email'], $input['password']);
            return [
                "status" => 200,
                "data" => [
                    "message" => "Login successful",
                    "token" => $userData['token'],
                    "user" => $userData['user']
                ]
            ];
        } catch (Exception $e) {
            return [
                "status" => 401,
                "data" => [
                    "error" => $e->getMessage()
                ]
            ];
        }
    }
}

