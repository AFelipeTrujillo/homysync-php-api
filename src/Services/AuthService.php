<?php

namespace App\Services;

use App\Core\UserRepositoryInterface;
use App\Domain\User;
use Exception;

class AuthService {

    public function __construct(private UserRepositoryInterface $userRepository) {} 

    public function register(string $email, string $password, string $name): User {
        if ($this->userRepository->findByEmail($email)) {
            throw new Exception("User with this email already exists.");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(null, $email, $passwordHash, $name);
        $this->userRepository->save($user);

        return $user;
    }
}