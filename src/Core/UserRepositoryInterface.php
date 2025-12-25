<?php

namespace App\Core;

use App\Domain\User;

interface UserRepositoryInterface
{
    /**
     * Finds a user by their email address.
     * @return User|null The user if found, null otherwise
     */
    public function findByEmail(string $email): ?User;
    /**
     * Saves a new user to the repository.
     * @return int The ID of the newly created user
     */
    public function save(User $user): int;
}