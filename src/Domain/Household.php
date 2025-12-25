<?php

namespace App\Domain;

class Household {
    public function __construct(
        public ?int $id,
        public string $name,
        public string $timezone,
        public int $cretedAt,
        public array $recipes = []
    ) {}
}