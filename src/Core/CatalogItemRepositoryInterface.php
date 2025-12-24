<?php

namespace App\Core;

use App\Domain\CatalogItem;

interface CatalogItemRepositoryInterface {
    public function save(CatalogItem $item): void;
    public function findByHousehold(int $householdId): array;
    public function delete(int $id): void;
}