<?php

namespace App\Services;

use App\Core\UserRepositoryInterface;
use App\Domain\User;
use Exception;

class AuthService {

    public function __construct(private UserRepositoryInterface $userRepository) {} 

    public function register(string $email, string $password, string $name): User 
    {
        if ($this->userRepository->findByEmail($email)) {
            throw new Exception("User with this email already exists.");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $email, $passwordHash, $name);
        $this->userRepository->save($user);

        return $user;
    }
    /**
     * Authenticate user and return user data
     * @param string $email
     * @param string $pasword
     * @return array
     */
    public function login(string $email, string $pasword): array 
    {
        $user = $this->userRepository->findByEmail($email);
        
        // Verify user exists and password matches
        if (!$user || !password_verify($pasword, $user->passwordHash)) {
            throw new Exception("Invalid email or password.");
        }

        // In a real application, you would generate a JWT or session token here
        // TODO: Implement token generation
        return [
            "id" => $user->id,
            "email" => $user->email,
            "name" => $user->name
        ];
    }
}