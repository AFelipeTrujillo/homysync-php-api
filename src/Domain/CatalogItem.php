<?php 

namespace App\Domain;

class CatalogItem {
    public function __construct(
        public ?int $id,
        public int $houseguId,
        public string $canonicalName,
        public string $category
    ) {}
}