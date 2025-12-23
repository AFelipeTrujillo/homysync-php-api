<?php

namespace App\Domain;

class User {
    public function __construct(
        public ?int $id,
        public string $email,
        public string $passwordHash,
        public string $name
    ) {}
}